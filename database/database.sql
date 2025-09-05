-- فایل: /database/database.sql
-- توضیحات: اسکریپت ساخت جداول پایگاه داده

-- ایجاد دیتابیس
CREATE DATABASE IF NOT EXISTS `cv123_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cv123_db`;

-- جدول کاربران
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    `status` ENUM('active', 'inactive', 'banned') NOT NULL DEFAULT 'inactive',
    `verification_code` VARCHAR(100) DEFAULT NULL,
    `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول پروفایل‌ها
CREATE TABLE IF NOT EXISTS `profiles` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `national_code` VARCHAR(10) DEFAULT NULL,
    `birth_date` DATE DEFAULT NULL,
    `gender` ENUM('male', 'female') DEFAULT NULL,
    `education` VARCHAR(100) DEFAULT NULL,
    `degree` VARCHAR(100) DEFAULT NULL,
    `field_of_study` VARCHAR(100) DEFAULT NULL,
    `university` VARCHAR(100) DEFAULT NULL,
    `province` VARCHAR(50) DEFAULT NULL,
    `city` VARCHAR(50) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `about` TEXT DEFAULT NULL,
    `photo` VARCHAR(255) DEFAULT NULL,
    `resume_file` VARCHAR(255) DEFAULT NULL,
    `linkedin` VARCHAR(255) DEFAULT NULL,
    `github` VARCHAR(255) DEFAULT NULL,
    `website` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول تجربیات کاری
CREATE TABLE IF NOT EXISTS `work_experiences` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `company` VARCHAR(100) NOT NULL,
    `position` VARCHAR(100) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE DEFAULT NULL,
    `current_job` TINYINT(1) NOT NULL DEFAULT 0,
    `description` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول مهارت‌ها
CREATE TABLE IF NOT EXISTS `skills` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول مهارت‌های کاربر
CREATE TABLE IF NOT EXISTS `user_skills` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `skill_id` INT(11) UNSIGNED NOT NULL,
    `level` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_skill` (`user_id`, `skill_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول موقعیت‌های شغلی
CREATE TABLE IF NOT EXISTS `jobs` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `description` TEXT NOT NULL,
    `requirements` TEXT NOT NULL,
    `benefits` TEXT DEFAULT NULL,
    `type` ENUM('full-time', 'part-time', 'remote', 'internship', 'contract') NOT NULL,
    `min_salary` INT(11) DEFAULT NULL,
    `max_salary` INT(11) DEFAULT NULL,
    `province` VARCHAR(50) NOT NULL,
    `city` VARCHAR(50) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `status` ENUM('open', 'closed') NOT NULL DEFAULT 'open',
    `expiry_date` DATE DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول مهارت‌های مورد نیاز شغل
CREATE TABLE IF NOT EXISTS `job_skills` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `job_id` INT(11) UNSIGNED NOT NULL,
    `skill_id` INT(11) UNSIGNED NOT NULL,
    `is_required` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `job_skill` (`job_id`, `skill_id`),
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول درخواست‌های شغلی
CREATE TABLE IF NOT EXISTS `applications` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `job_id` INT(11) UNSIGNED NOT NULL,
    `status` ENUM('pending', 'shortlisted', 'interviewed', 'accepted', 'rejected') NOT NULL DEFAULT 'pending',
    `cover_letter` TEXT DEFAULT NULL,
    `admin_notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_job` (`user_id`, `job_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول پیام‌ها
CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `sender_id` INT(11) UNSIGNED NOT NULL,
    `receiver_id` INT(11) UNSIGNED NOT NULL,
    `subject` VARCHAR(255) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول تنظیمات
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT DEFAULT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول گفتگوهای هوش مصنوعی
CREATE TABLE IF NOT EXISTS `ai_chats` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `message` TEXT NOT NULL,
    `response` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- درج داده‌های اولیه در جدول تنظیمات
INSERT INTO `settings` (`key`, `value`, `description`) VALUES
('site_name', 'مجتمع کامپیوتر یک دو سه', 'نام سایت'),
('site_description', 'سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه', 'توضیحات سایت'),
('admin_email', 'admin@cv123.ir', 'ایمیل مدیر'),
('openai_api_key', '', 'کلید API هوش مصنوعی OpenAI'),
('sms_username', '', 'نام کاربری سامانه پیامک'),
('sms_password', '', 'رمز عبور سامانه پیامک'),
('sms_sender', '', 'شماره فرستنده پیامک');

-- درج مدیر سیستم
INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`, `status`, `is_verified`) VALUES
('مدیر سیستم', 'admin@cv123.ir', '09123456789', '$2y$10$IlR4.QIK0XYsNR7zMO6uH.ntPbQPOEQ0p3nKKBrwnRLZrxOyMrE8m', 'admin', 'active', 1);
-- نکته: رمز عبور پیش‌فرض: Admin@123
