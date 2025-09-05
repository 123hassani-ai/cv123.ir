<?php
/**
 * فایل: /database/migrate_settings.php
 * توضیحات: اسکریپت انتقال تنظیمات از فایل JSON به دیتابیس
 */

// تعریف ثابت‌های پایه
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

// لود فایل کانفیگ
require_once __DIR__ . '/../config/config.php';

// لود کلاس Database
require_once __DIR__ . '/../app/helpers/Database.php';

// نمایش پیام‌ها
function showMessage($message, $isError = false) {
    echo ($isError ? "🔴 خطا: " : "🟢 ") . $message . PHP_EOL;
}

// مهاجرت تنظیمات از فایل JSON به دیتابیس
function migrateSettingsToDatabase() {
    try {
        $settingsFile = __DIR__ . '/../config/settings.json';
        
        // بررسی وجود فایل تنظیمات
        if (!file_exists($settingsFile)) {
            showMessage("فایل تنظیمات در مسیر $settingsFile یافت نشد.", true);
            return false;
        }
        
        // خواندن تنظیمات از فایل JSON
        $settings = json_decode(file_get_contents($settingsFile), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            showMessage("خطا در خواندن فایل JSON: " . json_last_error_msg(), true);
            return false;
        }
        
        // اتصال به دیتابیس
        $db = Database::getInstance();
        
        // شروع تراکنش
        $db->beginTransaction();
        
        // تعداد رکوردهای بروزرسانی شده
        $updatedCount = 0;
        
        // اضافه کردن تنظیمات به دیتابیس
        foreach ($settings as $key => $value) {
            // تبدیل مقادیر بولین به 0 و 1
            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            }
            
            // تعیین گروه تنظیمات
            $group = 'general';
            
            if (strpos($key, 'site_') === 0 || $key == 'debug_mode') {
                $group = 'general';
            } elseif (strpos($key, 'ai_') === 0 || $key == 'openai_api_key' || $key == 'enable_ai') {
                $group = 'ai';
            } elseif (strpos($key, 'sms_') === 0 || $key == 'enable_sms') {
                $group = 'sms';
            } elseif (strpos($key, 'footer_') === 0) {
                $group = 'footer';
            }
            
            // بروزرسانی یا درج تنظیم
            $db->query(
                "INSERT INTO settings (`key`, `value`, `group`) VALUES (?, ?, ?) 
                 ON DUPLICATE KEY UPDATE `value` = ?",
                [$key, $value, $group, $value]
            );
            
            $updatedCount++;
        }
        
        // تایید تراکنش
        $db->commit();
        
        showMessage("$updatedCount تنظیم با موفقیت به دیتابیس منتقل شد.");
        return true;
    } catch (Exception $e) {
        // برگرداندن تراکنش در صورت بروز خطا
        if (isset($db) && $db->inTransaction()) {
            $db->rollBack();
        }
        
        showMessage("خطا در انتقال تنظیمات: " . $e->getMessage(), true);
        return false;
    }
}

// نمایش عنوان
echo "=== انتقال تنظیمات از فایل JSON به دیتابیس ===" . PHP_EOL;

// اجرای فرآیند انتقال
$result = migrateSettingsToDatabase();

if ($result) {
    echo PHP_EOL . "✅ عملیات با موفقیت انجام شد." . PHP_EOL;
    echo "⚠️ توجه: فایل تنظیمات قدیمی را می‌توانید حذف یا به عنوان نسخه پشتیبان نگه‌دارید." . PHP_EOL;
} else {
    echo PHP_EOL . "❌ عملیات با خطا مواجه شد." . PHP_EOL;
}
