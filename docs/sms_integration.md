# راهنمای اتصال به سرویس پیامک ۰۰۹۸

این مستند، نحوه اتصال به سرویس پیامک ۰۰۹۸ و استفاده از آن در سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه را شرح می‌دهد.

## معرفی سرویس پیامک ۰۰۹۸

سرویس پیامک ۰۰۹۸ یک سامانه ارسال و دریافت پیامک است که امکانات زیر را فراهم می‌کند:

- ارسال پیامک تکی و گروهی
- ارسال پیامک زمان‌بندی شده
- دریافت پیامک (پیامک دو طرفه)
- ارسال پیامک با سرشماره اختصاصی
- ارسال پیامک هوشمند و پویا
- گزارش‌گیری از وضعیت پیامک‌ها

## پیش‌نیازها

برای استفاده از سرویس پیامک ۰۰۹۸، به موارد زیر نیاز دارید:

1. **حساب کاربری** در سامانه پیامک ۰۰۹۸
2. **نام کاربری و رمز عبور** API
3. **شماره فرستنده** تأیید شده

## تنظیمات اتصال

تنظیمات اتصال به سرویس پیامک در فایل `config/config.php` قرار دارند:

```php
// تنظیمات پیامک
define('SMS_USERNAME', '');  // نام کاربری سامانه پیامک
define('SMS_PASSWORD', '');  // رمز عبور سامانه پیامک
define('SMS_SENDER', '');    // شماره فرستنده پیامک
```

این مقادیر را با اطلاعات حساب کاربری خود در سامانه پیامک ۰۰۹۸ جایگزین کنید.

## کلاس رابط پیامک

برای سهولت استفاده از سرویس پیامک، یک کلاس رابط در پوشه `app/helpers` ایجاد شده است:

```php
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
    
    /**
     * سازنده کلاس
     */
    public function __construct()
    {
        $this->username = SMS_USERNAME;
        $this->password = SMS_PASSWORD;
        $this->sender = SMS_SENDER;
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
        
        // پارامترهای ارسال
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'to' => implode(',', $receptor),
            'from' => $sender,
            'text' => $message,
        ];
        
        // ارسال درخواست به API
        return $this->sendRequest('/send', $params);
    }
    
    /**
     * دریافت وضعیت پیامک
     * 
     * @param string $messageId شناسه پیام
     * @return array پاسخ API
     */
    public function getStatus($messageId)
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'id' => $messageId,
        ];
        
        return $this->sendRequest('/status', $params);
    }
    
    /**
     * دریافت اعتبار حساب
     * 
     * @return array پاسخ API
     */
    public function getCredit()
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
        ];
        
        return $this->sendRequest('/credit', $params);
    }
    
    /**
     * ارسال درخواست به API
     * 
     * @param string $endpoint نقطه پایانی API
     * @param array $params پارامترهای درخواست
     * @return array پاسخ API
     */
    private function sendRequest($endpoint, $params)
    {
        $url = $this->apiUrl . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $error,
                'http_code' => $httpCode
            ];
        }
        
        $result = json_decode($response, true);
        
        return $result ?: [
            'success' => false,
            'message' => 'Invalid response from API',
            'response' => $response,
            'http_code' => $httpCode
        ];
    }
}
```

## موارد استفاده

### 1. ارسال پیامک تأیید ثبت‌نام

```php
/**
 * ارسال کد تأیید به کاربر جدید
 * 
 * @param string $phoneNumber شماره موبایل کاربر
 * @param string $verificationCode کد تأیید
 * @return bool نتیجه ارسال
 */
public function sendVerificationCode($phoneNumber, $verificationCode)
{
    $smsService = new SmsService();
    $message = "کد تأیید شما در سامانه استخدام مجتمع کامپیوتر یک دو سه: $verificationCode";
    
    $result = $smsService->send($phoneNumber, $message);
    
    return isset($result['success']) && $result['success'] === true;
}
```

### 2. ارسال اطلاعیه درخواست شغلی

```php
/**
 * ارسال اطلاعیه درخواست شغلی
 * 
 * @param string $phoneNumber شماره موبایل کاربر
 * @param string $jobTitle عنوان شغل
 * @return bool نتیجه ارسال
 */
public function sendApplicationNotification($phoneNumber, $jobTitle)
{
    $smsService = new SmsService();
    $message = "درخواست شما برای شغل «$jobTitle» با موفقیت ثبت شد. نتیجه بررسی به شما اطلاع داده خواهد شد.";
    
    $result = $smsService->send($phoneNumber, $message);
    
    return isset($result['success']) && $result['success'] === true;
}
```

### 3. ارسال اطلاعیه وضعیت درخواست

```php
/**
 * ارسال اطلاعیه تغییر وضعیت درخواست
 * 
 * @param string $phoneNumber شماره موبایل کاربر
 * @param string $jobTitle عنوان شغل
 * @param string $status وضعیت جدید
 * @return bool نتیجه ارسال
 */
public function sendStatusUpdateNotification($phoneNumber, $jobTitle, $status)
{
    $statusText = '';
    switch ($status) {
        case 'shortlisted':
            $statusText = 'در لیست کوتاه';
            break;
        case 'interviewed':
            $statusText = 'دعوت به مصاحبه';
            break;
        case 'accepted':
            $statusText = 'پذیرفته شده';
            break;
        case 'rejected':
            $statusText = 'رد شده';
            break;
        default:
            $statusText = $status;
    }
    
    $smsService = new SmsService();
    $message = "وضعیت درخواست شما برای شغل «$jobTitle» تغییر کرد: $statusText";
    
    $result = $smsService->send($phoneNumber, $message);
    
    return isset($result['success']) && $result['success'] === true;
}
```

### 4. ارسال یادآوری مصاحبه

```php
/**
 * ارسال یادآوری مصاحبه
 * 
 * @param string $phoneNumber شماره موبایل کاربر
 * @param string $jobTitle عنوان شغل
 * @param string $interviewDate تاریخ و زمان مصاحبه
 * @param string $interviewLocation محل مصاحبه
 * @return bool نتیجه ارسال
 */
public function sendInterviewReminder($phoneNumber, $jobTitle, $interviewDate, $interviewLocation)
{
    $smsService = new SmsService();
    $message = "یادآوری: مصاحبه شما برای شغل «$jobTitle» در تاریخ $interviewDate در محل $interviewLocation برگزار می‌شود.";
    
    $result = $smsService->send($phoneNumber, $message);
    
    return isset($result['success']) && $result['success'] === true;
}
```

## مدیریت خطاها

هنگام ارسال پیامک، ممکن است خطاهایی رخ دهد. برای مدیریت این خطاها، از کد زیر استفاده کنید:

```php
try {
    $smsService = new SmsService();
    $result = $smsService->send($phoneNumber, $message);
    
    if (!isset($result['success']) || $result['success'] !== true) {
        // ثبت خطا در لاگ
        Logger::error('SMS sending failed', [
            'phone' => $phoneNumber,
            'error' => $result['message'] ?? 'Unknown error',
            'result' => $result
        ]);
        
        // انجام اقدامات جایگزین (مثلاً ارسال ایمیل)
        // ...
        
        return false;
    }
    
    return true;
} catch (Exception $e) {
    // ثبت خطا در لاگ
    Logger::error('SMS sending exception', [
        'phone' => $phoneNumber,
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    return false;
}
```

## تست اتصال به سرویس پیامک

برای تست اتصال به سرویس پیامک، یک اسکریپت تست در پوشه `tests` ایجاد کنید:

```php
<?php
/**
 * فایل: /tests/sms_test.php
 * توضیحات: اسکریپت تست اتصال به سرویس پیامک ۰۰۹۸
 */

// بارگذاری تنظیمات
require_once __DIR__ . '/../config/config.php';

// بارگذاری کلاس SmsService
require_once __DIR__ . '/../app/helpers/SmsService.php';

// شماره موبایل تست
$testPhoneNumber = '09123456789';  // شماره موبایل خود را وارد کنید

// ایجاد نمونه از کلاس SmsService
$smsService = new SmsService();

// نمایش اعتبار حساب
echo "Checking account credit...\n";
$creditResult = $smsService->getCredit();
print_r($creditResult);
echo "\n";

// ارسال پیامک تست
echo "Sending test SMS...\n";
$message = "این یک پیامک تست از سامانه استخدام مجتمع کامپیوتر یک دو سه است.";
$sendResult = $smsService->send($testPhoneNumber, $message);
print_r($sendResult);
echo "\n";

// بررسی وضعیت پیامک (در صورت موفقیت ارسال)
if (isset($sendResult['success']) && $sendResult['success'] === true && isset($sendResult['id'])) {
    echo "Checking SMS status...\n";
    $messageId = $sendResult['id'];
    $statusResult = $smsService->getStatus($messageId);
    print_r($statusResult);
}
```

برای اجرای این اسکریپت، دستور زیر را در ترمینال وارد کنید:

```bash
php /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/tests/sms_test.php
```

## عیب‌یابی

### مشکل: خطای ارتباط با سرور

**راه حل**: بررسی کنید که سرور به اینترنت دسترسی دارد و می‌تواند به سرور پیامک متصل شود. همچنین تنظیمات فایروال سرور را بررسی کنید.

### مشکل: خطای احراز هویت

**راه حل**: نام کاربری و رمز عبور API را بررسی کنید. همچنین مطمئن شوید که حساب کاربری شما فعال است و اعتبار کافی دارد.

### مشکل: خطای شماره فرستنده نامعتبر

**راه حل**: بررسی کنید که شماره فرستنده در پنل کاربری سامانه پیامک تأیید شده باشد.

### مشکل: عدم دریافت پیامک توسط گیرنده

**راه حل**:
1. وضعیت پیامک را با استفاده از API بررسی کنید.
2. شماره گیرنده را به فرمت صحیح وارد کنید.
3. اطمینان حاصل کنید که گیرنده در لیست سیاه نباشد.
4. بررسی کنید که محتوای پیامک حاوی کلمات فیلتر شده نباشد.

## منابع و مراجع

- [مستندات رسمی API سرویس پیامک ۰۰۹۸](https://0098sms.com/api-docs)
- [پنل کاربری سرویس پیامک ۰۰۹۸](https://panel.0098sms.com)
- [راهنمای عیب‌یابی سرویس پیامک ۰۰۹۸](https://0098sms.com/troubleshooting)
