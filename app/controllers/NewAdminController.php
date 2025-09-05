<?php
/**
 * فایل: /app/controllers/NewAdminController.php
 * توضیحات: کنترلر جدید بخش مدیریت با جداول تفکیک شده
 */

class NewAdminController
{
    private $generalSettings;
    private $smsSettings;
    private $aiSettings;
    
    public function __construct()
    {
        $this->generalSettings = new GeneralSettingsManager();
        $this->smsSettings = new SmsSettingsManager();
        $this->aiSettings = new AiSettingsManager();
    }
    
    /**
     * بررسی دسترسی مدیر
     */
    private function checkAdminAccess()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . '/admin/login');
            exit;
        }
    }
    
    /**
     * نمایش صفحه تنظیمات عمومی
     */
    public function generalSettings()
    {
        $this->checkAdminAccess();
        
        $settings = $this->generalSettings->getAllSettings();
        $pageTitle = 'تنظیمات عمومی';
        $viewPath = APP_PATH . '/views/admin/general-settings.php';
        
        require_once APP_PATH . '/views/layouts/admin.php';
    }
    
    /**
     * ذخیره تنظیمات عمومی
     */
    public function saveGeneralSettings()
    {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $settings = [
                    'site_title' => ['value' => $_POST['site_title'] ?? '', 'type' => 'text'],
                    'site_description' => ['value' => $_POST['site_description'] ?? '', 'type' => 'textarea'],
                    'footer_text' => ['value' => $_POST['footer_text'] ?? '', 'type' => 'textarea'],
                    'contact_email' => ['value' => $_POST['contact_email'] ?? '', 'type' => 'email'],
                    'contact_phone' => ['value' => $_POST['contact_phone'] ?? '', 'type' => 'text'],
                    'admin_email' => ['value' => $_POST['admin_email'] ?? '', 'type' => 'email'],
                    'maintenance_mode' => ['value' => isset($_POST['maintenance_mode']) ? '1' : '0', 'type' => 'boolean'],
                    'debug_mode' => ['value' => isset($_POST['debug_mode']) ? '1' : '0', 'type' => 'boolean'],
                    'items_per_page' => ['value' => $_POST['items_per_page'] ?? '20', 'type' => 'number']
                ];
                
                if ($this->generalSettings->updateMultipleSettings($settings)) {
                    $_SESSION['success_message'] = 'تنظیمات عمومی با موفقیت ذخیره شد';
                } else {
                    $_SESSION['error_message'] = 'خطا در ذخیره تنظیمات عمومی';
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'خطا در پردازش: ' . $e->getMessage();
            }
        }
        
        header('Location: ' . BASE_URL . '/admin/general-settings');
        exit;
    }
    
    /**
     * نمایش صفحه تنظیمات پیامک
     */
    public function smsSettings()
    {
        $this->checkAdminAccess();
        
        $settings = $this->smsSettings->getActiveSettings();
        $testSettings = $this->smsSettings->getTestSettings();
        $pageTitle = 'تنظیمات پیامک';
        $viewPath = APP_PATH . '/views/admin/sms-settings.php';
        
        require_once APP_PATH . '/views/layouts/admin.php';
    }
    
    /**
     * ذخیره تنظیمات پیامک
     */
    public function saveSmsSettings()
    {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $settings = [
                    'provider_name' => $_POST['sms_provider'] ?? '0098',
                    'username' => $_POST['sms_username'] ?? '',
                    'password' => $_POST['sms_password'] ?? '',
                    'domain' => $_POST['sms_domain'] ?? '0098',
                    'line_number' => $_POST['sms_line_number'] ?? '',
                    'test_phone' => $_POST['sms_test_phone'] ?? '09123456789',
                    'test_message' => $_POST['sms_test_message'] ?? 'پیام تست از سامانه CV123',
                    'max_daily_sms' => $_POST['max_daily_sms'] ?? 1000
                ];
                
                if ($this->smsSettings->updateSettings($settings)) {
                    $_SESSION['success_message'] = 'تنظیمات پیامک با موفقیت ذخیره شد';
                } else {
                    $_SESSION['error_message'] = 'خطا در ذخیره تنظیمات پیامک';
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'خطا در پردازش: ' . $e->getMessage();
            }
        }
        
        header('Location: ' . BASE_URL . '/admin/sms-settings');
        exit;
    }
    
    /**
     * تست اتصال پیامک
     */
    public function testSms()
    {
        $this->checkAdminAccess();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $testPhone = $_POST['test_phone'] ?? null;
            $testMessage = $_POST['test_message'] ?? null;
            
            $result = $this->smsSettings->testConnection($testPhone, $testMessage);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'خطا در تست پیامک: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    /**
     * نمایش صفحه تنظیمات هوش مصنوعی
     */
    public function aiSettings()
    {
        $this->checkAdminAccess();
        
        $settings = $this->aiSettings->getActiveSettings();
        $pageTitle = 'تنظیمات هوش مصنوعی';
        $viewPath = APP_PATH . '/views/admin/ai-settings.php';
        
        require_once APP_PATH . '/views/layouts/admin.php';
    }
    
    /**
     * ذخیره تنظیمات هوش مصنوعی
     */
    public function saveAiSettings()
    {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $settings = [
                    'provider_name' => $_POST['ai_provider'] ?? 'openai',
                    'api_key' => $_POST['openai_api_key'] ?? '',
                    'model' => $_POST['openai_model'] ?? 'gpt-3.5-turbo',
                    'max_tokens' => $_POST['openai_max_tokens'] ?? 1000,
                    'temperature' => $_POST['openai_temperature'] ?? 0.70,
                    'max_requests_per_hour' => $_POST['max_requests_per_hour'] ?? 100,
                    'system_prompt' => $_POST['system_prompt'] ?? 'شما یک دستیار هوشمند برای سیستم استخدام CV123 هستید.',
                    'timeout_seconds' => $_POST['timeout_seconds'] ?? 30
                ];
                
                // اعتبارسنجی
                $errors = $this->aiSettings->validateSettings($settings);
                if (!empty($errors)) {
                    $_SESSION['error_message'] = 'خطاهای اعتبارسنجی: ' . implode(', ', $errors);
                } else {
                    if ($this->aiSettings->updateSettings($settings)) {
                        $_SESSION['success_message'] = 'تنظیمات هوش مصنوعی با موفقیت ذخیره شد';
                    } else {
                        $_SESSION['error_message'] = 'خطا در ذخیره تنظیمات هوش مصنوعی';
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'خطا در پردازش: ' . $e->getMessage();
            }
        }
        
        header('Location: ' . BASE_URL . '/admin/ai-settings');
        exit;
    }
    
    /**
     * تست اتصال هوش مصنوعی
     */
    public function testAi()
    {
        $this->checkAdminAccess();
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $testPrompt = $_POST['test_prompt'] ?? 'سلام، این یک تست اتصال است.';
            $result = $this->aiSettings->testConnection($testPrompt);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'خطا در تست هوش مصنوعی: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    /**
     * نمایش داشبورد با آمار کلی
     */
    public function dashboard()
    {
        $this->checkAdminAccess();
        
        // جمع‌آوری آمار از بخش‌های مختلف
        $generalStats = $this->getGeneralStats();
        $smsStats = $this->smsSettings->getUsageStats();
        $aiStats = $this->aiSettings->getUsageStats();
        
        $pageTitle = 'داشبورد مدیریت';
        $viewPath = APP_PATH . '/views/admin/new-dashboard.php';
        
        require_once APP_PATH . '/views/layouts/admin.php';
    }
    
    /**
     * دریافت آمار عمومی سیستم
     */
    private function getGeneralStats()
    {
        try {
            $db = Database::getInstance();
            
            return [
                'users' => $db->query("SELECT COUNT(*) as count FROM users")->fetch()['count'] ?? 0,
                'jobs' => $db->query("SELECT COUNT(*) as count FROM jobs")->fetch()['count'] ?? 0,
                'applications' => $db->query("SELECT COUNT(*) as count FROM applications")->fetch()['count'] ?? 0,
                'active_chats' => $db->query("SELECT COUNT(*) as count FROM ai_chats WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)")->fetch()['count'] ?? 0
            ];
        } catch (Exception $e) {
            error_log('خطا در دریافت آمار عمومی: ' . $e->getMessage());
            return [
                'users' => 0,
                'jobs' => 0,
                'applications' => 0,
                'active_chats' => 0
            ];
        }
    }
}
