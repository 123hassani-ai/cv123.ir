<?php
/**
 * فایل: /app/views/layouts/admin.php
 * توضیحات: قالب اصلی بخش مدیریت
 */
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="پنل مدیریت <?php echo APP_DESCRIPTION; ?>">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . APP_NAME : APP_NAME; ?> - پنل مدیریت</title>
    
    <!-- فونت وزیرمتن -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/fonts/vazirmatn/vazirmatn.css">
    
    <!-- بوت استرپ ۵ -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/bootstrap.rtl.min.css">
    
    <!-- آیکون‌های Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- استایل‌های اختصاصی -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/admin.css">
    
    <!-- آیکون‌ها -->
    <link rel="icon" href="<?php echo BASE_URL; ?>/public/images/favicon.ico">
    
    <?php if (isset($extraStyles)) echo $extraStyles; ?>
</head>
<body class="admin-dashboard">
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- سایدبار -->
            <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 bg-dark admin-sidebar-wrapper">
                <?php include_once APP_PATH . '/views/admin/components/sidebar.php'; ?>
            </div>
            
            <!-- محتوای اصلی -->
            <div class="col-md-9 col-lg-10 ms-auto py-4 px-4 content-wrapper">
                <!-- هدر پنل مدیریت -->
                <header class="mb-4">
                    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded p-3">
                        <div class="container-fluid">
                            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ms-auto">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-person-circle me-1"></i> مدیر سیستم
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/profile">پروفایل</a></li>
                                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/settings">تنظیمات</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout">خروج</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </header>
                
                <!-- پیام‌های سیستم -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info'; ?> alert-dismissible fade show">
                        <?php 
                        echo $_SESSION['message']; 
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="بستن"></button>
                    </div>
                <?php endif; ?>
                
                <!-- محتوای اصلی -->
                <main>
                    <?php 
                    // اگر از طریق AdminController و با قالب جدید فراخوانی شده باشد
                    if (!isset($viewPath)) {
                        // محتوا در محل گنجانده شده است
                        echo "<!-- محتوا در محل گنجانده شده -->";
                    } else {
                        // محتوا از طریق فایل اضافه می‌شود
                        include_once $viewPath;
                    }
                    ?>
                </main>
                
                <!-- فوتر پنل مدیریت -->
                <footer class="mt-5 pt-4 border-top">
                    <p class="text-center text-muted small"><?php echo APP_NAME; ?> &copy; <?php echo date('Y'); ?> - نسخه <?php echo APP_VERSION; ?></p>
                </footer>
            </div>
        </div>
    </div>
    
    <!-- جاوااسکریپت‌ها -->
    <script src="<?php echo BASE_URL; ?>/public/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/js/admin.js"></script>
    
    <?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
