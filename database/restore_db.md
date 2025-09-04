# راهنمای بازیابی دیتابیس

این راهنما به شما کمک می‌کند تا دیتابیس پروژه CV123 را در سیستم جدید خود بازیابی کنید.

## پیش‌نیازها
- XAMPP نصب شده باشد
- MySQL روی پورت 3307 در حال اجرا باشد
- کاربر `root` با رمز عبور `Mojtab@123` تنظیم شده باشد

## مراحل بازیابی

1. **بازیابی از فایل بک‌آپ**

```bash
# در ترمینال، به مسیر پروژه بروید
cd /Applications/XAMPP/xamppfiles/htdocs/cv123.ir

# دیتابیس را ایجاد کنید (اگر وجود ندارد)
/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'Mojtab@123' -P 3307 -e "CREATE DATABASE IF NOT EXISTS cv123_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# فایل بک‌آپ را به دیتابیس وارد کنید
/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'Mojtab@123' -P 3307 cv123_db < cv123_db_backup.sql
```

2. **بررسی بازیابی**

```bash
# بررسی کنید که جداول به درستی بازیابی شده‌اند
/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'Mojtab@123' -P 3307 -e "USE cv123_db; SHOW TABLES;"
```

## نکات مهم

- در صورت تغییر پورت MySQL، دستورات فوق را متناسب با پورت خود تغییر دهید.
- در صورت تغییر رمز عبور کاربر `root`، دستورات فوق را متناسب با رمز عبور خود تغییر دهید.
- برای اجرا در سیستم‌عامل‌های مختلف، ممکن است مسیر دستورات متفاوت باشد.

## بازیابی در سی‌پنل

برای بازیابی دیتابیس در هاست اشتراکی با سی‌پنل:

1. وارد PHPMyAdmin شوید
2. یک دیتابیس جدید با نام `cv123_db` ایجاد کنید (یا نام دلخواه)
3. در صفحه دیتابیس، روی تب "Import" کلیک کنید
4. فایل `cv123_db_backup.sql` را انتخاب کنید و دکمه "Go" را بزنید
5. فایل `config.php` در پوشه `config/` را ویرایش کنید و اطلاعات دیتابیس جدید را وارد کنید:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'نام_کاربری_دیتابیس');
   define('DB_PASS', 'رمز_عبور_دیتابیس');
   define('DB_NAME', 'نام_دیتابیس');
   define('DB_PORT', 3306); // پورت پیش‌فرض در سی‌پنل
   ```
