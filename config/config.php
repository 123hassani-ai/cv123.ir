<?php
/**
 * فایل: /config/config.php
 * توضیحات: تنظیمات اصلی پروژه
 */

// تنظیمات اصلی
define('APP_NAME', 'cv123');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'https://cv123.ir');
define('APP_DESCRIPTION', 'سیستم مدیریت استخدام نیرو در مجتمع کامپیوتر یک دو سه');

// تنظیمات دیتابیس
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');
define('DB_NAME', 'cv123_db');
define('DB_USER', 'root');
define('DB_PASS', 'Mojtab@123');

// تنظیمات پیامک
define('SMS_USERNAME', '');
define('SMS_PASSWORD', '');
define('SMS_SENDER', '');

// تنظیمات OpenAI
define('OPENAI_API_KEY', '');

// تنظیمات خطایابی
define('DEBUG_MODE', true);
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// تنظیم منطقه زمانی
date_default_timezone_set('Asia/Tehran');

// تنظیمات مسیرها (با بررسی تعریف قبلی)
define('BASE_URL', '/cv123.ir');  // مسیر پایه سایت (در محیط محلی /cv123.ir)
define('ASSETS_URL', BASE_URL . '/public');
if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(__DIR__) . '/app');  // مسیر پوشه app
}
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));  // مسیر ریشه پروژه
}
?>
