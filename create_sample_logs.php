<?php
// فایل ایجاد لاگ‌های نمونه بیشتر
// مسیر: create_sample_logs.php

// تعریف ثوابت مورد نیاز
define('APP_PATH', __DIR__);

require_once 'app/helpers/Autoloader.php';
Autoloader::register();

echo "<h2>ایجاد لاگ‌های نمونه</h2>";

try {
    // لاگ‌های مختلف با انواع مختلف
    for ($i = 1; $i <= 20; $i++) {
        $levels = ['INFO', 'ERROR', 'DEBUG', 'WARNING'];
        $categories = ['auth', 'database', 'api', 'user', 'system'];
        $users = ['admin', 'user123', 'guest', 'test'];
        
        $level = $levels[array_rand($levels)];
        $category = $categories[array_rand($categories)];
        $user = $users[array_rand($users)];
        
        // پیام‌های مختلف
        $messages = [
            'INFO' => [
                ['کاربر وارد سیستم شد', 'User logged in'],
                ['صفحه بارگذاری شد', 'Page loaded'],
                ['درخواست پردازش شد', 'Request processed']
            ],
            'ERROR' => [
                ['خطا در اتصال به دیتابیس', 'Database connection error'], 
                ['فایل یافت نشد', 'File not found'],
                ['دسترسی غیرمجاز', 'Unauthorized access']
            ],
            'DEBUG' => [
                ['اطلاعات دیباگ', 'Debug information'],
                ['متغیر بررسی شد', 'Variable checked'],
                ['تابع فراخوانی شد', 'Function called']
            ],
            'WARNING' => [
                ['هشدار امنیتی', 'Security warning'],
                ['حافظه کم است', 'Low memory'],
                ['فایل قدیمی است', 'File is outdated']
            ]
        ];
        
        $msgArray = $messages[$level][array_rand($messages[$level])];
        
        // ثبت لاگ
        switch ($level) {
            case 'INFO':
                Logger::info($msgArray[0], $msgArray[1], ['user' => $user]);
                break;
            case 'ERROR':
                Logger::error($msgArray[0], $msgArray[1], ['user' => $user]);
                break;
            case 'DEBUG':
                Logger::debug($msgArray[0], $msgArray[1], ['user' => $user]);
                break;
            case 'WARNING':
                Logger::warning($msgArray[0], $msgArray[1], ['user' => $user]);
                break;
        }
        
        // تاخیر کوتاه برای زمان‌های مختلف
        usleep(100000); // 0.1 ثانیه
    }
    
    echo "<p>✅ 20 لاگ نمونه با موفقیت ایجاد شد</p>";
    echo "<p>🔗 برای مشاهده لاگ‌ها به <a href='admin/logs'>پنل مدیریت</a> بروید</p>";
    
} catch (Exception $e) {
    echo "<p>❌ خطا در ایجاد لاگ‌ها: " . $e->getMessage() . "</p>";
}
?>
