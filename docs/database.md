# مستندات پایگاه داده سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه

این مستند، ساختار پایگاه داده و جداول مورد استفاده در پروژه را شرح می‌دهد.

## اطلاعات کلی پایگاه داده

- **نام پایگاه داده**: `cv123_db`
- **سیستم پایگاه داده**: MySQL 8.0 / MariaDB
- **پورت**: 3307 (XAMPP)
- **کاراکترست**: utf8mb4_persian_ci (برای پشتیبانی کامل از زبان فارسی)
- **نام کاربری پیش‌فرض**: `root`
- **رمز عبور پیش‌فرض**: `Mojtab@123`

## اتصال به پایگاه داده

### در محیط XAMPP macOS
سیستم به طور خودکار از Unix Socket استفاده می‌کند:
- **Socket Path**: `/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock`
- **DSN**: `mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname=cv123_db;charset=utf8mb4`

### در سایر محیط‌ها
- **Host**: localhost
- **Port**: 3307 (یا 3306 برای تنظیمات استاندارد)
- **DSN**: `mysql:host=localhost;port=3307;dbname=cv123_db;charset=utf8mb4`

## دیاگرام ساختاری (ER Diagram)

دیاگرام ارتباطی جداول به صورت ساده:

```
users 1---* profiles
users 1---* work_experiences
users 1---* user_skills
users 1---* applications
users 1---* messages (sender)
users 1---* messages (receiver)
users 1---* ai_chats
jobs 1---* applications
jobs 1---* job_skills
skills 1---* user_skills
skills 1---* job_skills
```

## شرح جداول

### جدول `users`

جدول اصلی کاربران سیستم که اطلاعات حساب کاربری را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| name | VARCHAR(100) | نام کامل کاربر |
| email | VARCHAR(100) | ایمیل کاربر (یکتا) |
| phone | VARCHAR(20) | شماره تلفن کاربر |
| password | VARCHAR(255) | رمز عبور رمزنگاری شده |
| role | ENUM | نقش کاربر: 'user' یا 'admin' |
| status | ENUM | وضعیت کاربر: 'active', 'inactive', 'banned' |
| verification_code | VARCHAR(100) | کد تأیید حساب کاربری |
| is_verified | TINYINT | آیا حساب کاربری تأیید شده است؟ |
| created_at | TIMESTAMP | زمان ایجاد |
| updated_at | TIMESTAMP | زمان آخرین به‌روزرسانی |

### جدول `profiles`

اطلاعات تکمیلی پروفایل کاربران را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| user_id | INT | کلید خارجی به جدول users |
| national_code | VARCHAR(10) | کد ملی |
| birth_date | DATE | تاریخ تولد |
| gender | ENUM | جنسیت: 'male' یا 'female' |
| education | VARCHAR(100) | تحصیلات |
| degree | VARCHAR(100) | مدرک تحصیلی |
| field_of_study | VARCHAR(100) | رشته تحصیلی |
| university | VARCHAR(100) | دانشگاه |
| province | VARCHAR(50) | استان |
| city | VARCHAR(50) | شهر |
| address | TEXT | آدرس کامل |
| about | TEXT | درباره من |
| photo | VARCHAR(255) | مسیر فایل عکس پروفایل |
| resume_file | VARCHAR(255) | مسیر فایل رزومه |
| linkedin | VARCHAR(255) | آدرس لینکدین |
| github | VARCHAR(255) | آدرس گیت‌هاب |
| website | VARCHAR(255) | آدرس وب‌سایت شخصی |
| created_at | TIMESTAMP | زمان ایجاد |
| updated_at | TIMESTAMP | زمان آخرین به‌روزرسانی |

### جدول `work_experiences`

تجربیات کاری کاربران را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| user_id | INT | کلید خارجی به جدول users |
| company | VARCHAR(100) | نام شرکت |
| position | VARCHAR(100) | عنوان شغلی |
| start_date | DATE | تاریخ شروع |
| end_date | DATE | تاریخ پایان |
| current_job | TINYINT | آیا شغل فعلی است؟ |
| description | TEXT | توضیحات |
| created_at | TIMESTAMP | زمان ایجاد |
| updated_at | TIMESTAMP | زمان آخرین به‌روزرسانی |

### جدول `skills`

لیست مهارت‌های موجود در سیستم را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| name | VARCHAR(100) | نام مهارت |
| slug | VARCHAR(100) | نام انگلیسی مهارت |
| category | VARCHAR(50) | دسته‌بندی مهارت |
| created_at | TIMESTAMP | زمان ایجاد |
| updated_at | TIMESTAMP | زمان آخرین به‌روزرسانی |

### جدول `settings`

تنظیمات سامانه را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| key | VARCHAR(50) | کلید تنظیم (کلید اصلی) |
| value | TEXT | مقدار تنظیم |
| group | VARCHAR(50) | گروه تنظیم (عمومی، هوش مصنوعی، پیامک، فوتر و غیره) |
| created_at | TIMESTAMP | زمان ایجاد |
| updated_at | TIMESTAMP | زمان آخرین به‌روزرسانی |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| name | VARCHAR(100) | نام مهارت |
| category | VARCHAR(100) | دسته‌بندی مهارت |
| created_at | TIMESTAMP | زمان ایجاد |

### جدول `user_skills`

مهارت‌های هر کاربر را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| user_id | INT | کلید خارجی به جدول users |
| skill_id | INT | کلید خارجی به جدول skills |
| level | TINYINT | سطح مهارت (1-5) |
| created_at | TIMESTAMP | زمان ایجاد |

### جدول `jobs`

موقعیت‌های شغلی را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| title | VARCHAR(100) | عنوان شغلی |
| description | TEXT | توضیحات شغل |
| requirements | TEXT | شرایط لازم |
| benefits | TEXT | مزایا |
| type | ENUM | نوع همکاری: 'full-time', 'part-time', 'remote', 'internship', 'contract' |
| min_salary | INT | حداقل حقوق |
| max_salary | INT | حداکثر حقوق |
| province | VARCHAR(50) | استان |
| city | VARCHAR(50) | شهر |
| category | VARCHAR(100) | دسته‌بندی شغلی |
| status | ENUM | وضعیت: 'open' یا 'closed' |
| expiry_date | DATE | تاریخ انقضا |
| created_at | TIMESTAMP | زمان ایجاد |
| updated_at | TIMESTAMP | زمان آخرین به‌روزرسانی |

### جدول `job_skills`

مهارت‌های مورد نیاز برای هر شغل را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| job_id | INT | کلید خارجی به جدول jobs |
| skill_id | INT | کلید خارجی به جدول skills |
| is_required | TINYINT | آیا مهارت الزامی است؟ |
| created_at | TIMESTAMP | زمان ایجاد |

### جدول `applications`

درخواست‌های استخدام را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| user_id | INT | کلید خارجی به جدول users |
| job_id | INT | کلید خارجی به جدول jobs |
| status | ENUM | وضعیت درخواست: 'pending', 'shortlisted', 'interviewed', 'accepted', 'rejected' |
| cover_letter | TEXT | نامه معرفی |
| admin_notes | TEXT | یادداشت‌های مدیر |
| created_at | TIMESTAMP | زمان ایجاد |
| updated_at | TIMESTAMP | زمان آخرین به‌روزرسانی |

### جدول `messages`

پیام‌های بین کاربران و مدیران را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| sender_id | INT | کلید خارجی به جدول users (فرستنده) |
| receiver_id | INT | کلید خارجی به جدول users (گیرنده) |
| subject | VARCHAR(255) | موضوع پیام |
| message | TEXT | متن پیام |
| is_read | TINYINT | آیا پیام خوانده شده است؟ |
| created_at | TIMESTAMP | زمان ارسال |

### جدول `settings`

تنظیمات سیستم را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| key | VARCHAR(100) | کلید تنظیم (یکتا) |
| value | TEXT | مقدار تنظیم |
| description | VARCHAR(255) | توضیحات |
| updated_at | TIMESTAMP | زمان آخرین به‌روزرسانی |

### جدول `ai_chats`

گفتگوهای کاربران با هوش مصنوعی را نگهداری می‌کند.

| فیلد | نوع | توضیحات |
|------|-----|---------|
| id | INT | کلید اصلی، خودکار افزایشی |
| user_id | INT | کلید خارجی به جدول users |
| message | TEXT | پیام کاربر |
| response | TEXT | پاسخ هوش مصنوعی |
| created_at | TIMESTAMP | زمان گفتگو |

## داده‌های پیش‌فرض

هنگام نصب اولیه پایگاه داده، برخی از داده‌های پیش‌فرض به صورت خودکار ایجاد می‌شوند:

1. **کاربر مدیر پیش‌فرض**:
   - ایمیل: admin@cv123.ir
   - رمز عبور: Admin@123

2. **تنظیمات پایه**:
   - نام سایت
   - توضیحات سایت
   - ایمیل مدیر
   - تنظیمات API هوش مصنوعی
   - تنظیمات سامانه پیامک

## نحوه نصب و راه‌اندازی پایگاه داده

برای نصب و راه‌اندازی پایگاه داده، دو روش وجود دارد:

### روش 1: استفاده از اسکریپت نصب

1. مرورگر را باز کنید و به آدرس زیر بروید:
   ```
   http://localhost/cv123.ir/database/setup.php
   ```

2. اسکریپت به صورت خودکار دیتابیس و جداول را ایجاد می‌کند.

### روش 2: اجرای مستقیم اسکریپت SQL

1. ابتدا دیتابیس را ایجاد کنید:
   ```sql
   CREATE DATABASE cv123_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. سپس فایل SQL را اجرا کنید:
   ```bash
   mysql -u root -p'Mojtab@123' cv123_db < /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/database/database.sql
   ```
