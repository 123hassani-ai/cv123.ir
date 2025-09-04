<?php
/**
 * فایل: /app/controllers/HomeController.php
 * توضیحات: کنترلر صفحه اصلی سایت
 */

class HomeController
{
    /**
     * نمایش صفحه اصلی
     */
    public function index()
    {
        $pageTitle = 'صفحه اصلی';
        $viewPath = APP_PATH . '/views/home.php';
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
}
?>
