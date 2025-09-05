<?php
require_once 'SimpleLogger.php';

echo "🔧 تست لاگر ساده...\n";

try {
    SimpleLogger::info('تست اول');
    echo "✅ لاگ اول ثبت شد\n";
    
    SimpleLogger::error('تست خطا');
    echo "✅ لاگ دوم ثبت شد\n";
    
    // بررسی فایل
    $logFiles = glob('logs/simple_*.json');
    if (!empty($logFiles)) {
        $file = end($logFiles);
        $size = filesize($file);
        echo "📏 حجم فایل: {$size} بایت\n";
        echo "💡 حافظه: " . round(memory_get_usage()/1024, 0) . " KB\n";
    }
    
    echo "🎉 تست موفق!\n";
    
} catch (Exception $e) {
    echo "❌ خطا: " . $e->getMessage() . "\n";
}
