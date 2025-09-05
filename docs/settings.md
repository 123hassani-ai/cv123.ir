## مدیریت تنظیمات

سیستم مدیریت تنظیمات از دیتابیس برای ذخیره و بازیابی تنظیمات استفاده می‌کند. این تنظیمات در جدول `settings` ذخیره می‌شوند.

### ساختار جدول تنظیمات

جدول `settings` دارای ساختار زیر است:

```sql
CREATE TABLE IF NOT EXISTS `settings` (
  `key` VARCHAR(50) NOT NULL PRIMARY KEY,
  `value` TEXT,
  `group` VARCHAR(50) DEFAULT 'general',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;
```

### گروه‌های تنظیمات

تنظیمات به گروه‌های زیر تقسیم می‌شوند:

- **general**: تنظیمات عمومی سایت (نام سایت، URL، توضیحات و...)
- **ai**: تنظیمات مربوط به هوش مصنوعی (کلید API، مدل، دستورالعمل‌ها)
- **sms**: تنظیمات مربوط به پیامک (سرویس‌دهنده، نام کاربری، رمز عبور)
- **footer**: تنظیمات محتوای فوتر (متن، آدرس، تلفن، ایمیل و شبکه‌های اجتماعی)
- **other**: سایر تنظیمات

### نحوه دسترسی به تنظیمات

برای دسترسی به تنظیمات در کنترلرها یا مدل‌ها، از کلاس `Database` استفاده کنید:

```php
// دریافت همه تنظیمات
$db = Database::getInstance();
$result = $db->query("SELECT `key`, `value` FROM settings")->fetchAll();

// دریافت تنظیمات یک گروه خاص
$db = Database::getInstance();
$result = $db->query("SELECT `key`, `value` FROM settings WHERE `group` = ?", ['ai'])->fetchAll();

// دریافت یک تنظیم خاص
$db = Database::getInstance();
$result = $db->query("SELECT `value` FROM settings WHERE `key` = ?", ['openai_api_key'])->fetch();
$apiKey = $result['value'] ?? '';
```

### کلاس‌های سرویس

کلاس‌های سرویس مانند `OpenAIService` و `SmsService` به طور خودکار تنظیمات مربوطه را از دیتابیس دریافت می‌کنند.

### پنل مدیریت تنظیمات

در پنل مدیریت، صفحه تنظیمات به شما امکان می‌دهد:

1. تنظیمات عمومی سایت را تغییر دهید
2. تنظیمات OpenAI را پیکربندی کنید
3. تنظیمات پیامک را پیکربندی کنید
4. محتوای فوتر را ویرایش کنید
5. API‌های OpenAI و پیامک را تست کنید

### مهاجرت از فایل JSON به دیتابیس

اگر نیاز به مهاجرت تنظیمات از فایل JSON به دیتابیس دارید، به مستند `database/settings_migration.md` مراجعه کنید.
