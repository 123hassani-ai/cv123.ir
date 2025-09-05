-- تفکیک جداول تنظیمات به بخش‌های مختلف
-- ایجاد: 1403/06/14

-- جدول تنظیمات عمومی سیستم
CREATE TABLE IF NOT EXISTS general_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    description VARCHAR(255),
    setting_type ENUM('text', 'textarea', 'number', 'email', 'url', 'boolean', 'json') DEFAULT 'text',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول تنظیمات پیامک
CREATE TABLE IF NOT EXISTS sms_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_name VARCHAR(50) NOT NULL DEFAULT '0098',
    username VARCHAR(100),
    password VARCHAR(100),
    domain VARCHAR(100),
    line_number VARCHAR(20),
    test_phone VARCHAR(15) DEFAULT '09123456789',
    test_message TEXT DEFAULT 'پیام تست از سامانه CV123',
    max_daily_sms INT DEFAULT 1000,
    is_active BOOLEAN DEFAULT TRUE,
    api_endpoint VARCHAR(255),
    backup_provider VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول تنظیمات هوش مصنوعی
CREATE TABLE IF NOT EXISTS ai_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_name VARCHAR(50) NOT NULL DEFAULT 'openai',
    api_key VARCHAR(255),
    model VARCHAR(100) DEFAULT 'gpt-3.5-turbo',
    max_tokens INT DEFAULT 1000,
    temperature DECIMAL(3,2) DEFAULT 0.70,
    max_requests_per_hour INT DEFAULT 100,
    system_prompt TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    backup_provider VARCHAR(50),
    timeout_seconds INT DEFAULT 30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- درج داده‌های اولیه تنظیمات عمومی
INSERT INTO general_settings (setting_key, setting_value, description, setting_type) VALUES
('site_title', 'CV123 - سامانه استخدام', 'عنوان سایت', 'text'),
('site_description', 'سامانه هوشمند استخدام نیروی انسانی', 'توضیحات سایت', 'textarea'),
('footer_text', '© ۱۴۰۳ مجتمع کامپیوتر یک دو سه - تمامی حقوق محفوظ است', 'متن فوتر سایت', 'textarea'),
('contact_email', 'info@cv123.ir', 'ایمیل تماس', 'email'),
('contact_phone', '021-88888888', 'تلفن تماس', 'text'),
('admin_email', 'admin@cv123.ir', 'ایمیل مدیر سیستم', 'email'),
('site_logo', 'logo-site.png', 'نام فایل لوگو', 'text'),
('maintenance_mode', '0', 'حالت تعمیرات سایت', 'boolean'),
('debug_mode', '0', 'حالت دیباگ', 'boolean'),
('items_per_page', '20', 'تعداد آیتم در هر صفحه', 'number');

-- درج داده‌های اولیه تنظیمات پیامک
INSERT INTO sms_settings (provider_name, username, password, domain, line_number, test_phone, test_message, api_endpoint) VALUES
('0098', '', '', '0098', '', '09123456789', 'پیام تست از سامانه CV123', 'https://0098sms.com/sendsmslink.aspx');

-- درج داده‌های اولیه تنظیمات هوش مصنوعی
INSERT INTO ai_settings (provider_name, api_key, model, max_tokens, temperature, system_prompt) VALUES
('openai', '', 'gpt-3.5-turbo', 1000, 0.70, 'شما یک دستیار هوشمند برای سیستم استخدام CV123 هستید. پاسخ‌های مفید و دقیق ارائه دهید.');
