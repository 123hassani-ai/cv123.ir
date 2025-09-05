-- ایجاد جدول تنظیمات
CREATE TABLE IF NOT EXISTS `settings` (
  `key` VARCHAR(50) NOT NULL PRIMARY KEY,
  `value` TEXT,
  `group` VARCHAR(50) DEFAULT 'general',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_persian_ci;

-- درج مقادیر پیش‌فرض
INSERT INTO `settings` (`key`, `value`, `group`) VALUES
-- تنظیمات عمومی
('site_name', 'CV123', 'general'),
('site_url', 'https://cv123.ir', 'general'),
('site_description', 'سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه', 'general'),
('debug_mode', '0', 'general'),

-- تنظیمات هوش مصنوعی
('openai_api_key', '', 'ai'),
('ai_model', 'gpt-3.5-turbo', 'ai'),
('ai_instructions', 'شما یک دستیار هوشمند استخدام برای مجتمع کامپیوتر یک دو سه هستید. به سوالات متقاضیان به صورت دقیق، مفید و مودبانه پاسخ دهید.', 'ai'),
('enable_ai', '1', 'ai'),

-- تنظیمات پیامک
('sms_provider', '0098', 'sms'),
('sms_username', '', 'sms'),
('sms_password', '', 'sms'),
('sms_sender', '', 'sms'),
('enable_sms', '1', 'sms'),

-- محتوای فوتر
('footer_text', 'سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه', 'footer'),
('footer_address', 'تهران، خیابان آزادی، مجتمع کامپیوتر یک دو سه', 'footer'),
('footer_phone', '۰۲۱-۱۲۳۴۵۶۷۸', 'footer'),
('footer_email', 'info@cv123.ir', 'footer'),
('footer_instagram', 'cv123ir', 'footer'),
('footer_telegram', 'cv123ir', 'footer'),
('footer_linkedin', 'cv123ir', 'footer'),
('footer_twitter', 'cv123ir', 'footer');
