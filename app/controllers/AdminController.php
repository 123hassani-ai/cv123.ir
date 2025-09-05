<?php
/**
 * فایل: /app/controllers/AdminController.php
 * توضیحات: کنترلر بخش مدیریت
 */

class AdminController
{
    /**
     * نمایش داشبورد مدیریت
     */
    public function dashboard()
    {
        // بررسی دسترسی مدیر
        $this->checkAdminAccess();
        
        // دریافت آمار برای نمایش در داشبورد
        $stats = $this->getStats();
        
        // تنظیم متغیرهای layout
        $page_title = 'داشبورد مدیریت';
        $current_page = 'dashboard';
        $breadcrumbs = [];
        $content_file = __DIR__ . '/../views/admin/dashboard_content.php';
        
        // نمایش layout
        require_once __DIR__ . '/../views/admin/layouts/admin_layout.php';
    }
    
    /**
     * دریافت آمار سیستم
     */
    private function getStats()
    {
        // در نسخه اولیه، آمار فرضی برمی‌گردانیم
        return [
            'applications' => 752,
            'jobs' => 48,
            'users' => 256,
            'interviews' => 124
        ];
    }
    
    /**
     * نمایش صفحه ورود مدیر
     */
    public function showLogin()
    {
        $pageTitle = 'ورود مدیر';
        $viewPath = APP_PATH . '/views/admin/login.php';
        
        // اگر مدیر قبلاً وارد شده است، به داشبورد هدایت شود
        if (isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * پردازش فرم ورود مدیر
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // بررسی اطلاعات ورود آزمایشی
            if ($username === 'admin' && $password === '123456') {
                // ایجاد سشن مدیر
                $_SESSION['admin_id'] = 1;
                $_SESSION['admin_username'] = 'admin';
                $_SESSION['admin_name'] = 'مدیر سیستم';
                
                // نمایش پیام موفقیت
                $_SESSION['message'] = 'خوش آمدید! با موفقیت وارد شدید.';
                $_SESSION['message_type'] = 'success';
                
                // هدایت به داشبورد
                header('Location: ' . BASE_URL . '/admin');
                exit;
            } else {
                // نمایش پیام خطا
                $_SESSION['message'] = 'نام کاربری یا رمز عبور اشتباه است.';
                $_SESSION['message_type'] = 'danger';
                
                // بازگشت به صفحه ورود
                header('Location: ' . BASE_URL . '/admin/login');
                exit;
            }
        }
        
        // اگر درخواست GET باشد، به صفحه ورود هدایت می‌شود
        header('Location: ' . BASE_URL . '/admin/login');
        exit;
    }
    
    /**
     * بررسی دسترسی مدیر
     */
    private function checkAdminAccess()
    {
        // در نسخه اولیه، اگر کاربر وارد نشده باشد یا مدیر نباشد، به صفحه ورود هدایت می‌شود
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . '/admin/login');
            exit;
        }
    }
    
    /**
     * نمایش صفحه تنظیمات سیستم
     */
    public function settings()
    {
        // بررسی دسترسی مدیر
        $this->checkAdminAccess();
        
        // دریافت تنظیمات فعلی
        $settings = $this->getSettings();
        
        $pageTitle = 'تنظیمات سیستم';
        $viewPath = APP_PATH . '/views/admin/settings.php';
        
        // نمایش صفحه تنظیمات
        require_once APP_PATH . '/views/layouts/admin.php';
    }
    
    /**
     * ذخیره تنظیمات سیستم
     */
    public function saveSettings()
    {
        // بررسی دسترسی مدیر
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // دریافت داده‌های فرم
            $settings = [
                // تنظیمات عمومی
                'site_name' => $_POST['site_name'] ?? APP_NAME,
                'site_url' => $_POST['site_url'] ?? BASE_URL,
                'site_description' => $_POST['site_description'] ?? '',
                'debug_mode' => isset($_POST['debug_mode']) ? true : false,
                
                // تنظیمات هوش مصنوعی
                'openai_api_key' => $_POST['openai_api_key'] ?? '',
                'ai_model' => $_POST['ai_model'] ?? 'gpt-3.5-turbo',
                'ai_instructions' => $_POST['ai_instructions'] ?? '',
                'enable_ai' => isset($_POST['enable_ai']) ? true : false,
                
                // تنظیمات پیامک
                'sms_provider' => $_POST['sms_provider'] ?? '0098',
                'sms_username' => $_POST['sms_username'] ?? '',
                'sms_password' => $_POST['sms_password'] ?? '',
                'sms_sender' => $_POST['sms_sender'] ?? '',
                'enable_sms' => isset($_POST['enable_sms']) ? true : false,
                
                // محتوای فوتر
                'footer_text' => $_POST['footer_text'] ?? '',
                'footer_address' => $_POST['footer_address'] ?? '',
                'footer_phone' => $_POST['footer_phone'] ?? '',
                'footer_email' => $_POST['footer_email'] ?? '',
                'footer_instagram' => $_POST['footer_instagram'] ?? '',
                'footer_telegram' => $_POST['footer_telegram'] ?? '',
                'footer_linkedin' => $_POST['footer_linkedin'] ?? '',
                'footer_twitter' => $_POST['footer_twitter'] ?? '',
            ];
            
            // ذخیره تنظیمات
            if ($this->updateSettings($settings)) {
                $_SESSION['message'] = 'تنظیمات با موفقیت ذخیره شد.';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'خطا در ذخیره‌سازی تنظیمات.';
                $_SESSION['message_type'] = 'danger';
            }
        }
        
        // هدایت به صفحه تنظیمات
        header('Location: ' . BASE_URL . '/admin/settings');
        exit;
    }
    
    /**
     * دریافت تنظیمات فعلی
     */
    private function getSettings()
    {
        try {
            // استفاده از کلاس Database برای دریافت تنظیمات از دیتابیس
            $db = Database::getInstance();
            $result = $db->query("SELECT `key`, `value` FROM settings")->fetchAll();
            
            // تبدیل نتایج به آرایه تنظیمات
            $settings = [];
            foreach ($result as $row) {
                // تبدیل مقادیر 0 و 1 به بولین برای فیلدهای فعال/غیرفعال
                if (in_array($row['key'], ['debug_mode', 'enable_ai', 'enable_sms'])) {
                    $settings[$row['key']] = (bool)$row['value'];
                } else {
                    $settings[$row['key']] = $row['value'];
                }
            }
            
            return $settings;
        } catch (Exception $e) {
            // ثبت خطا در لاگ
            error_log('خطا در دریافت تنظیمات: ' . $e->getMessage());
            
            // برگرداندن مقادیر پیش‌فرض در صورت بروز خطا
            return [
                'site_name' => APP_NAME,
                'site_url' => BASE_URL,
                'site_description' => 'سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه',
                'debug_mode' => false,
                'openai_api_key' => OPENAI_API_KEY ?? '',
                'ai_model' => 'gpt-3.5-turbo',
                'ai_instructions' => 'شما یک دستیار هوشمند استخدام هستید.',
                'enable_ai' => true,
                'sms_provider' => '0098',
                'sms_username' => SMS_USERNAME ?? '',
                'sms_password' => SMS_PASSWORD ?? '',
                'sms_sender' => SMS_SENDER ?? '',
                'sms_test_message' => 'تست ارسال پیامک از سامانه CV123',
                'sms_test_number' => '09123456789',
                'enable_sms' => true,
                'footer_text' => 'سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه',
                'footer_address' => 'تهران، خیابان آزادی، مجتمع کامپیوتر یک دو سه',
                'footer_phone' => '۰۲۱-۱۲۳۴۵۶۷۸',
                'footer_email' => 'info@cv123.ir',
                'footer_instagram' => 'cv123ir',
                'footer_telegram' => 'cv123ir',
                'footer_linkedin' => 'cv123ir',
                'footer_twitter' => 'cv123ir'
            ];
        }
    }
    
    /**
     * بروزرسانی تنظیمات
     */
    private function updateSettings($settings)
    {
        try {
            // گرفتن نمونه پایگاه داده
            $db = Database::getInstance();
            
            // شروع تراکنش
            $db->beginTransaction();
            
            // بروزرسانی هر تنظیم
            foreach ($settings as $key => $value) {
                // تبدیل مقادیر بولین به 0 و 1 برای ذخیره در دیتابیس
                if (is_bool($value)) {
                    $value = $value ? '1' : '0';
                }
                
                // تعیین گروه تنظیمات
                $group = $this->getSettingGroup($key);
                
                // بروزرسانی یا درج تنظیم
                $db->query(
                    "INSERT INTO settings (`key`, `value`, `group_name`) VALUES (?, ?, ?) 
                     ON DUPLICATE KEY UPDATE `value` = ?, `group_name` = ?",
                    [$key, $value, $group, $value, $group]
                );
            }
            
            // تایید تراکنش
            $db->commit();
            
            return true;
        } catch (Exception $e) {
            // برگرداندن تراکنش در صورت بروز خطا
            if ($db && $db->inTransaction()) {
                $db->rollBack();
            }
            
            // ثبت خطا در لاگ
            error_log('خطا در ذخیره تنظیمات: ' . $e->getMessage());
            
            return false;
        }
    }
    
    /**
     * تعیین گروه تنظیمات بر اساس کلید
     */
    private function getSettingGroup($key)
    {
        if (strpos($key, 'site_') === 0 || $key == 'debug_mode') {
            return 'general';
        } elseif (strpos($key, 'ai_') === 0 || $key == 'openai_api_key' || $key == 'enable_ai') {
            return 'ai';
        } elseif (strpos($key, 'sms_') === 0 || $key == 'enable_sms') {
            return 'sms';
        } elseif (strpos($key, 'footer_') === 0) {
            return 'footer';
        } else {
            return 'other';
        }
    }
    
    /**
     * نمایش صفحه مدیریت محتوا
     */
    public function content()
    {
        // بررسی دسترسی مدیر
        $this->checkAdminAccess();
        
        $pageTitle = 'مدیریت محتوا';
        $viewPath = APP_PATH . '/views/admin/content.php';
        
        // نمایش صفحه مدیریت محتوا
        require_once APP_PATH . '/views/layouts/admin.php';
    }
    
    /**
     * ذخیره محتوای سایت
     */
    public function saveContent()
    {
        // بررسی دسترسی مدیر
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // دریافت اطلاعات فرم
            $content = [
                // صفحه اصلی
                'home_title' => $_POST['home_title'] ?? '',
                'home_subtitle' => $_POST['home_subtitle'] ?? '',
                // اگر تصویر بنر آپلود شده باشد
                // 'home_banner' => $this->uploadImage('home_banner'),
                
                // ویژگی‌های سیستم (به صورت آرایه)
                'features' => [],
                
                // درباره ما
                'about_title' => $_POST['about_title'] ?? '',
                'about_content' => $_POST['about_content'] ?? '',
                // 'about_image' => $this->uploadImage('about_image'),
                
                // تماس با ما
                'contact_title' => $_POST['contact_title'] ?? '',
                'contact_text' => $_POST['contact_text'] ?? '',
                'contact_address' => $_POST['contact_address'] ?? '',
                'contact_email' => $_POST['contact_email'] ?? '',
                'contact_phone' => $_POST['contact_phone'] ?? '',
                'contact_hours' => $_POST['contact_hours'] ?? '',
                'contact_map' => $_POST['contact_map'] ?? '',
                
                // سوالات متداول
                'faq_title' => $_POST['faq_title'] ?? '',
                'faq_intro' => $_POST['faq_intro'] ?? '',
                'faqs' => []
            ];
            
            // استخراج ویژگی‌های سیستم
            if (isset($_POST['feature_title']) && is_array($_POST['feature_title'])) {
                for ($i = 0; $i < count($_POST['feature_title']); $i++) {
                    if (!empty($_POST['feature_title'][$i])) {
                        $content['features'][] = [
                            'icon' => $_POST['feature_icon'][$i] ?? 'bi-gear',
                            'title' => $_POST['feature_title'][$i],
                            'desc' => $_POST['feature_desc'][$i] ?? ''
                        ];
                    }
                }
            }
            
            // استخراج سوالات متداول
            if (isset($_POST['faq_question']) && is_array($_POST['faq_question'])) {
                for ($i = 0; $i < count($_POST['faq_question']); $i++) {
                    if (!empty($_POST['faq_question'][$i])) {
                        $content['faqs'][] = [
                            'question' => $_POST['faq_question'][$i],
                            'answer' => $_POST['faq_answer'][$i] ?? ''
                        ];
                    }
                }
            }
            
            // ذخیره محتوا در دیتابیس یا فایل
            if ($this->saveContentToFile($content)) {
                // نمایش پیام موفقیت
                $_SESSION['message'] = 'محتوای سایت با موفقیت ذخیره شد.';
                $_SESSION['message_type'] = 'success';
            } else {
                // نمایش پیام خطا
                $_SESSION['message'] = 'خطا در ذخیره‌سازی محتوا.';
                $_SESSION['message_type'] = 'danger';
            }
        }
        
        // بازگشت به صفحه مدیریت محتوا
        header('Location: ' . BASE_URL . '/admin/content');
        exit;
    }
    
    /**
     * آپلود تصویر
     */
    private function uploadImage($fieldName)
    {
        // در نسخه واقعی، اینجا باید کد آپلود تصویر قرار گیرد
        // ...
        
        return '';
    }
    
    /**
     * ذخیره محتوا در فایل
     */
    private function saveContentToFile($content)
    {
        $contentFile = APP_PATH . '/../config/content.json';
        
        try {
            // ایجاد دایرکتوری config اگر وجود نداشته باشد
            if (!is_dir(dirname($contentFile))) {
                mkdir(dirname($contentFile), 0755, true);
            }
            
            // ذخیره محتوا به صورت JSON
            file_put_contents($contentFile, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            return true;
        } catch (Exception $e) {
            // ثبت خطا در لاگ
            error_log('خطا در ذخیره محتوا: ' . $e->getMessage());
            
            return false;
        }
    }
}
