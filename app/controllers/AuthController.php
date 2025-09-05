<?php
/**
 * فایل: /app/controllers/AuthController.php
 * توضیحات: کنترلر احراز هویت
 */

class AuthController
{
    /**
     * نمایش صفحه ورود
     */
    public function showLogin()
    {
        $pageTitle = 'ورود';
        $viewPath = APP_PATH . '/views/auth/login.php';
        
        // اگر کاربر قبلاً وارد شده است، به صفحه اصلی هدایت شود
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * پردازش فرم ورود
     */
    public function login()
    {
        // در نسخه اولیه، فقط یک پیام نمایش می‌دهیم
        $_SESSION['message'] = 'این قابلیت هنوز پیاده‌سازی نشده است.';
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
    
    /**
     * نمایش صفحه ثبت‌نام
     */
    public function showRegister()
    {
        $pageTitle = 'ثبت‌نام';
        $viewPath = APP_PATH . '/views/auth/register.php';
        
        // اگر کاربر قبلاً وارد شده است، به صفحه اصلی هدایت شود
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    /**
     * پردازش فرم ثبت‌نام
     */
    public function register()
    {
        // در نسخه اولیه، فقط یک پیام نمایش می‌دهیم
        $_SESSION['message'] = 'این قابلیت هنوز پیاده‌سازی نشده است.';
        header('Location: ' . BASE_URL . '/register');
        exit;
    }
    
    /**
     * خروج از سیستم
     */
    public function logout()
    {
        // از بین بردن نشست کاربر
        session_unset();
        session_destroy();
        
        // هدایت به صفحه اصلی
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}
