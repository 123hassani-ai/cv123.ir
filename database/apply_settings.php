<?php
/**
 * فایل: /database/apply_settings.php
 * توضیحات: اسکریپت اجرای تغییرات دیتابیس برای جدول تنظیمات
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

// اجرای اسکریپت SQL
function executeSqlFile($filePath) {
    try {
        // خواندن محتوای فایل SQL
        $sql = file_get_contents($filePath);
        
        if (!$sql) {
            showMessage("فایل SQL در مسیر $filePath یافت نشد یا خالی است.", true);
            return false;
        }
        
        // اتصال به دیتابیس
        $db = Database::getInstance();
        
        // اجرای دستورات SQL
        // جدا کردن دستورات با ;
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function($statement) {
                return !empty($statement);
            }
        );
        
        // اجرای هر دستور
        foreach ($statements as $statement) {
            $db->query($statement);
        }
        
        showMessage("اسکریپت SQL با موفقیت اجرا شد.");
        return true;
    } catch (Exception $e) {
        showMessage("خطا در اجرای اسکریپت SQL: " . $e->getMessage(), true);
        return false;
    }
}

// نمایش عنوان
echo "=== اجرای اسکریپت ایجاد و بروزرسانی جدول تنظیمات ===" . PHP_EOL;

// اجرای فایل SQL
$result = executeSqlFile(__DIR__ . '/settings.sql');

if ($result) {
    echo PHP_EOL . "✅ عملیات با موفقیت انجام شد." . PHP_EOL;
} else {
    echo PHP_EOL . "❌ عملیات با خطا مواجه شد." . PHP_EOL;
}
