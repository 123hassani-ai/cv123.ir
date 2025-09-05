<?php
/**
 * فایل: /database/setup.php
 * توضیحات: اسکریپت ایجاد پایگاه داده
 */

// بارگذاری تنظیمات
require_once __DIR__ . '/../config/config.php';

// اتصال به MySQL
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT, 
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
        ]
    );
    
    echo "اتصال به MySQL با موفقیت انجام شد.<br>";
    
    // ایجاد دیتابیس
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "دیتابیس " . DB_NAME . " با موفقیت ایجاد شد.<br>";
    
    // انتخاب دیتابیس
    $pdo->exec("USE `" . DB_NAME . "`");
    
    // خواندن فایل SQL
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // اجرای کوئری‌های SQL
    $pdo->exec($sql);
    
    echo "جداول با موفقیت ایجاد شدند.<br>";
    echo "نصب دیتابیس با موفقیت انجام شد.<br>";
    echo "<a href='../index.php'>بازگشت به صفحه اصلی</a>";
    
} catch (PDOException $e) {
    die("خطا در اتصال به پایگاه داده: " . $e->getMessage());
}
?>
