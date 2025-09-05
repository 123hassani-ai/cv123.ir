# راهنمای اتصال به سرویس پیامک

این مستند، نحوه اتصال به سرویس‌های پیامک مختلف و استفاده از آن‌ها در سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه را شرح می‌دهد.

## سرویس‌دهندگان پشتیبانی شده

سامانه از سه سرویس‌دهنده پیامک پشتیبانی می‌کند:

### 1. سامانه پیامک ۰۰۹۸ (پیش‌فرض)

سرویس پیامک ۰۰۹۸ یک سامانه ارسال و دریافت پیامک است که امکانات زیر را فراهم می‌کند:

- ارسال پیامک تکی و گروهی
- ارسال پیامک زمان‌بندی شده  
- دریافت پیامک (پیامک دو طرفه)
- ارسال پیامک با سرشماره اختصاصی
- گزارش‌گیری از وضعیت پیامک‌ها

**API Endpoint**: `https://0098sms.com/sendsmslink.aspx`

### 2. SMS.ir
**API Endpoint**: `https://api.sms.ir/v1`

### 3. کاوه‌نگار (Kavenegar)
**API Endpoint**: `https://api.kavenegar.com/v1`

## پیش‌نیازها

برای استفاده از سرویس‌های پیامک، به موارد زیر نیاز دارید:

1. **حساب کاربری** در یکی از سرویس‌دهندگان
2. **نام کاربری و رمز عبور** API  
3. **شماره فرستنده** تأیید شده

## تنظیمات در پنل مدیریت

### مسیر دسترسی
1. وارد پنل مدیریت شوید: `{BASE_URL}/admin`
2. به بخش تنظیمات بروید: `{BASE_URL}/admin/settings`  
3. تب "تنظیمات پیامک" را انتخاب کنید

### امکانات پنل مدیریت
در این بخش می‌توانید:

- **انتخاب سرویس‌دهنده**: ۰۰۹۸، SMS.ir، یا کاوه‌نگار
- **وارد کردن اطلاعات حساب**: نام کاربری و رمز عبور
- **تنظیم شماره فرستنده**: شماره اختصاصی شما
- **فعال/غیرفعال کردن سرویس**: کنترل وضعیت پیامک
- **تست ارسال**: ارسال پیامک آزمایشی

## تست ارسال پیامک

### مراحل تست
1. اطلاعات حساب کاربری را در فرم وارد کنید
2. یک شماره موبایل معتبر برای تست وارد کنید (مثال: 09123456789)
3. روی دکمه "ارسال پیامک تست" کلیک کنید
4. نتیجه در کنسول مرورگر و پیام‌های سیستم نمایش داده می‌شود

### عیب‌یابی
اگر تست موفق نبود:

1. **کنسول مرورگر را بررسی کنید** (F12 → Console)
2. **فایل‌های لاگ سرور را بررسی کنید** (`logs/database_errors.log`)
3. **اطلاعات حساب کاربری را تأیید کنید**
4. **شماره فرستنده را بررسی کنید**

## کدهای خطای سامانه پیامک ۰۰۹۸

هنگام تست یا ارسال پیامک، ممکن است با کدهای خطای زیر مواجه شوید:

| کد خطا | توضیحات |
|--------|----------|
| 0 | ✅ عملیات با موفقیت انجام شد |
| 1 | ❌ شماره گیرنده اشتباه است |
| 2 | ❌ گیرنده تعریف نشده است |
| 3 | ❌ فرستنده تعریف نشده است |
| 4 | ❌ متن تنظیم نشده است |
| 5 | ❌ نام کاربری تنظیم نشده است |
| 6 | ❌ کلمه عبور تنظیم نشده است |
| 7 | ❌ نام دامین تنظیم نشده است |
| 8 | ❌ مجوز شما باطل شده است |
| 9 | ❌ اعتبار پیامک شما کافی نیست |
| 10 | ❌ برای این شماره لینک تعریف نشده است |
| 11 | ❌ عدم مجوز برای اتصال لینک |
| 12 | ❌ نام کاربری و کلمه عبور اشتباه است |
| 13 | ❌ کاراکتر غیرمجاز در متن وجود دارد |
| 14 | ❌ سقف ارسال روزانه پر شده است |
| 16 | ❌ عدم مجوز شماره برای ارسال از لینک |
| 17 | ❌ خطا در شماره پنل. لطفا با پشتیبانی تماس بگیرید |
| 18 | ❌ اتمام تاریخ اعتبار شماره پنل |
| 19 | ❌ تنظیمات کد opt انجام نشده است |
| 20 | ❌ فرمت کد opt صحیح نیست |
| 21 | ❌ تنظیمات کد opt توسط ادمین تایید نشده است |
| 22 | ❌ اطلاعات مالک شماره ثبت و تایید نشده است |
| 23 | ❌ هنوز اجازه ارسال به این شماره پنل داده نشده است |
| 24 | ❌ ارسال از IP غیرمجاز انجام شده است |

## لاگ‌گیری و عیب‌یابی

### فایل‌های لاگ
- **لاگ‌های دیتابیس**: `logs/database_errors.log`
- **لاگ‌های PHP**: در محیط توسعه خطاها در کنسول نمایش داده می‌شوند

### بررسی لاگ‌های سرور
برای مشاهده لاگ‌های سرور:

```bash
tail -f /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/logs/database_errors.log
```

### دیباگ از طریق کنسول مرورگر
در مرورگر F12 را فشار دهید و به تب Console بروید. هنگام تست پیامک، اطلاعات تفصیلی نمایش داده می‌شود.

## استفاده برنامه‌نویسی

### کلاس SmsService

کلاس اصلی برای ارسال پیامک `SmsService` است که در `app/helpers/SmsService.php` قرار دارد.

```php
use SmsService;

// ایجاد نمونه
$sms = new SmsService();

// ارسال پیامک
$result = $sms->send('09123456789', 'متن پیام تست', '3000164545');

if ($result['error']) {
    // خطا در ارسال
    echo 'خطا: ' . $result['message'];
    if (isset($result['code'])) {
        echo ' (کد: ' . $result['code'] . ')';
    }
} else {
    // ارسال موفق
    echo 'پیامک با موفقیت ارسال شد';
}
```

### متدهای موجود

- `send($receptor, $message, $sender)`: ارسال پیامک
- `getSettings()`: دریافت تنظیمات از دیتابیس  
- `get0098ErrorMessage($code)`: ترجمه کدهای خطای ۰۰۹۸
}
```
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
