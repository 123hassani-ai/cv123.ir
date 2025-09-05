<?php
/**
 * فایل: /index.php
 * توضیحات: فایل اصلی پروژه که درخواست‌های کاربر را مدیریت می‌کند
 */

// تنظیم محیط اجرا
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// بارگذاری تنظیمات
require_once CONFIG_PATH . '/config.php';

// بارگذاری کلاس‌های اصلی
require_once APP_PATH . '/helpers/Autoloader.php';

// ثبت Autoloader
Autoloader::register();

// شروع نشست
session_start();

// اجرای روتر اصلی
require_once APP_PATH . '/Router.php';
?>
