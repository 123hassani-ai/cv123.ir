<?php
/**
 * فایل: /app/controllers/AdminController.php
 * توضیحات: کنترلر مدیریت سیستم
 */

class AdminController {
    /**
     * خروج از حساب کاربری مدیر
     */
    public function logout()
    {
        // حذف تمام متغیرهای جلسه
        $_SESSION = array();
        
        // حذف کوکی جلسه
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // نابودی جلسه
        session_destroy();
        
        // هدایت به صفحه ورود
        header('Location: ' . BASE_URL . '/admin/login');
        exit;
    }
}
