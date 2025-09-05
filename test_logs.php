<?php
// فایل تست سیستم لاگ‌گیری
// مسیر: test_logs.php

// تعریف ثوابت مورد نیاز
define('APP_PATH', __DIR__);

require_once 'app/helpers/Autoloader.php';
Autoloader::register();

echo "<h2>تست سیستم لاگ‌گیری CV123</h2>";

try {
    // تست انواع مختلف لاگ
    Logger::info('تست لاگ معمولی', 'Testing normal log');
    Logger::error('تست خطا', 'Testing error log');
    Logger::database('تست دیتابیس', 'Testing database log', ['query' => 'SELECT * FROM users']);
    Logger::api('تست API', 'Testing API log', ['endpoint' => '/test', 'status' => 200]);
    Logger::auth('تست احراز هویت', 'Testing auth log', ['user_id' => 123]);
    
    echo "<p>✅ همه لاگ‌ها با موفقیت ثبت شدند</p>";
    echo "<p>📂 لاگ‌ها در پوشه logs/ ذخیره می‌شوند</p>";
    echo "<p>🔗 برای مشاهده لاگ‌ها به <a href='admin/logs'>پنل مدیریت</a> بروید</p>";
    
} catch (Exception $e) {
    echo "<p>❌ خطا در تست: " . $e->getMessage() . "</p>";
}

// نمایش وضعیت فایل‌های لاگ
$logDir = __DIR__ . '/logs/';
if (is_dir($logDir)) {
    $files = glob($logDir . '*.json');
    echo "<h3>فایل‌های لاگ موجود:</h3>";
    echo "<ul>";
    foreach ($files as $file) {
        $size = filesize($file);
        echo "<li>" . basename($file) . " (" . round($size/1024, 2) . " KB)</li>";
    }
    echo "</ul>";
} else {
    echo "<p>⚠️ پوشه logs وجود ندارد</p>";
}
?>
