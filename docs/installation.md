# راهنمای نصب و راه‌اندازی سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه

این مستند، مراحل نصب و راه‌اندازی پروژه را توضیح می‌دهد.

## پیش‌نیازها

قبل از نصب و راه‌اندازی پروژه، اطمینان حاصل کنید که موارد زیر نصب و فعال باشند:

1. **وب سرور**: Apache 2.4 یا بالاتر
2. **پایگاه داده**: MySQL 8.0 یا MariaDB 10.4 یا بالاتر
3. **PHP**: نسخه 8.1 یا بالاتر
4. **XAMPP**: ترجیحاً از XAMPP برای سهولت نصب استفاده کنید

### افزونه‌های مورد نیاز PHP

- php-mysqli یا php-pdo_mysql
- php-json
- php-mbstring
- php-curl
- php-fileinfo
- php-gd
- php-xml

## مراحل نصب

### 1. دانلود و استقرار فایل‌ها

1. کد پروژه را از مخزن دانلود کنید یا فایل‌ها را در مسیر مناسب کپی کنید:
   ```
   /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/
   ```

2. اطمینان حاصل کنید که دسترسی‌های مناسب برای پوشه‌ها تنظیم شده باشد:
   ```bash
   sudo chown -R <username>:staff /Applications/XAMPP/xamppfiles/htdocs/cv123.ir
   sudo chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/cv123.ir
   ```

### 2. تنظیم پیکربندی

1. فایل `config/config.php` را باز کرده و تنظیمات مورد نیاز را انجام دهید:
   ```php
   // تنظیمات پایگاه داده
   define('DB_HOST', 'localhost');
   define('DB_PORT', '3307');  // پورت MySQL را تنظیم کنید
   define('DB_NAME', 'cv123_db');
   define('DB_USER', 'root');
   define('DB_PASS', 'Mojtab@123');  // رمز عبور MySQL را تنظیم کنید
   
   // تنظیمات پیامک
   define('SMS_USERNAME', '');  // نام کاربری سامانه پیامک
   define('SMS_PASSWORD', '');  // رمز عبور سامانه پیامک
   define('SMS_SENDER', '');    // شماره فرستنده پیامک
   
   // تنظیمات OpenAI
   define('OPENAI_API_KEY', ''); // کلید API هوش مصنوعی OpenAI
   ```

### 3. ایجاد پایگاه داده

برای ایجاد پایگاه داده، دو روش وجود دارد:

#### روش 1: استفاده از اسکریپت نصب

1. مرورگر را باز کرده و به آدرس زیر بروید:
   ```
   http://localhost/cv123.ir/database/setup.php
   ```

2. مراحل نصب را دنبال کنید تا پایگاه داده به طور کامل ایجاد شود.

#### روش 2: اجرای مستقیم اسکریپت SQL

1. ابتدا دیتابیس را ایجاد کنید:
   ```bash
   /Applications/XAMPP/xamppfiles/bin/mysql -u root -p'Mojtab@123' -e "CREATE DATABASE cv123_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

2. سپس فایل SQL را اجرا کنید:
   ```bash
   /Applications/XAMPP/xamppfiles/bin/mysql -u root -p'Mojtab@123' cv123_db < /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/database/database.sql
   ```

### 4. تنظیم وب سرور

اگر از XAMPP استفاده می‌کنید، وب سرور Apache باید از قبل تنظیم شده باشد. در غیر این صورت، یک VirtualHost برای پروژه ایجاد کنید:

1. فایل تنظیمات Apache را باز کنید:
   ```bash
   sudo nano /Applications/XAMPP/xamppfiles/etc/httpd.conf
   ```

2. مطمئن شوید که ماژول `mod_rewrite` فعال باشد:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```

3. فایل `httpd-vhosts.conf` را باز کنید:
   ```bash
   sudo nano /Applications/XAMPP/xamppfiles/etc/extra/httpd-vhosts.conf
   ```

4. یک VirtualHost برای پروژه اضافه کنید:
   ```apache
   <VirtualHost *:80>
       ServerName cv123.local
       DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs/cv123.ir"
       
       <Directory "/Applications/XAMPP/xamppfiles/htdocs/cv123.ir">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
       
       ErrorLog "logs/cv123-error_log"
       CustomLog "logs/cv123-access_log" common
   </VirtualHost>
   ```

5. فایل hosts سیستم را ویرایش کنید:
   ```bash
   sudo nano /etc/hosts
   ```
   
   و خط زیر را اضافه کنید:
   ```
   127.0.0.1 cv123.local
   ```

6. وب سرور Apache را راه‌اندازی مجدد کنید:
   ```bash
   sudo /Applications/XAMPP/xamppfiles/xampp restart
   ```

### 5. ایجاد پوشه‌های مورد نیاز

برخی پوشه‌ها نیاز به دسترسی نوشتن دارند. اطمینان حاصل کنید که این پوشه‌ها وجود دارند و دسترسی‌های لازم را دارند:

```bash
mkdir -p /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/logs
mkdir -p /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/public/uploads/resumes
mkdir -p /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/public/uploads/photos
mkdir -p /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/public/uploads/documents

chmod -R 777 /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/logs
chmod -R 777 /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/public/uploads
```

### 6. تست نصب

1. مرورگر را باز کرده و به آدرس زیر بروید:
   ```
   http://localhost/cv123.ir/
   ```
   یا اگر از VirtualHost استفاده کرده‌اید:
   ```
   http://cv123.local/
   ```

2. صفحه اصلی سایت باید به درستی نمایش داده شود.

## ورود به پنل مدیریت

پس از نصب موفق، می‌توانید با اطلاعات زیر وارد پنل مدیریت شوید:

- **آدرس**: http://localhost/cv123.ir/admin
- **نام کاربری**: admin@cv123.ir
- **رمز عبور**: Admin@123

## عیب‌یابی

### مشکل: خطای "دسترسی غیرمجاز" هنگام اجرای فایل‌ها

**راه حل**: دسترسی‌های پوشه پروژه را بررسی کنید:
```bash
sudo chown -R <username>:staff /Applications/XAMPP/xamppfiles/htdocs/cv123.ir
sudo chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/cv123.ir
```

### مشکل: خطای "پایگاه داده یافت نشد"

**راه حل**: مطمئن شوید که دیتابیس به درستی ایجاد شده است:
```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'Mojtab@123' -e "SHOW DATABASES;"
```

### مشکل: خطای "عدم دسترسی به فایل‌ها"

**راه حل**: مطمئن شوید که Apache با کاربر مناسب اجرا می‌شود و دسترسی به پوشه‌ها دارد.

### مشکل: Rewrite نادرست URL ها

**راه حل**: مطمئن شوید که ماژول `mod_rewrite` فعال است و فایل `.htaccess` در ریشه پروژه وجود دارد:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /cv123.ir/

    # اگر فایل یا پوشه وجود نداشته باشد
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # هدایت به index.php
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```
