<?php
/**
 * فایل: app/views/admin/layouts/admin_layout.php
 * توضیحات: لایوت اصلی پنل ادمین - هدر و سایدبار ثابت
 */

// بررسی احراز هویت ادمین
if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/login');
    exit;
}

// بارگذاری کانفیگ در صورت عدم وجود
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../config/config.php';
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'پنل مدیریت'; ?> - CV123</title>
    
    <!-- CSS اصلی -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- فونت وزیرمتن -->
    <link href="<?php echo BASE_URL; ?>/public/fonts/vazirmatn/vazirmatn.css" rel="stylesheet">
    
    <!-- CSS سفارشی ادمین -->
    <style>
        * {
            font-family: 'Vazirmatn', 'Tahoma', sans-serif !important;
        }
        
        body {
            background-color: #f8f9fa;
            font-size: 14px;
        }
        
        /* سایدبار */
        .admin-sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            right: 0;
            width: 250px;
            z-index: 1000;
        }
        
        .admin-sidebar .nav-link {
            color: #ecf0f1 !important;
            padding: 12px 20px;
            margin: 2px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .admin-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
            transform: translateX(-3px);
        }
        
        .admin-sidebar .nav-link.active {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.3);
        }
        
        .admin-sidebar .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }
        
        .admin-sidebar .sidebar-brand h5 {
            color: #ffffff;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        
        .admin-sidebar .sidebar-brand small {
            color: #bdc3c7;
        }
        
        /* محتوای اصلی */
        .admin-main {
            margin-right: 250px;
            padding: 0;
            min-height: 100vh;
        }
        
        /* هدر */
        .admin-header {
            background: #ffffff;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .admin-header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: bold;
        }
        
        .admin-header .breadcrumb {
            background: none;
            margin: 0;
            padding: 0;
        }
        
        /* محتوا */
        .admin-content {
            padding: 30px;
        }
        
        /* کارت‌ها */
        .card {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 20px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        /* دکمه‌ها */
        .btn {
            border-radius: 8px;
            font-weight: bold;
            padding: 8px 16px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9 0%, #1f5f99 100%);
        }
        
        /* فرم‌ها */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 12px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        /* جدول‌ها */
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            color: #2c3e50;
            font-weight: bold;
            padding: 12px;
        }
        
        .table td {
            padding: 12px;
            vertical-align: middle;
        }
        
        .table-hover tbody tr:hover {
            background-color: #f1f3f4;
        }
        
        /* Badge ها */
        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.75em;
            border-radius: 6px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .admin-main {
                margin-right: 0;
            }
        }
        
        /* CSS اضافی برای صفحه خاص */
        <?php if (isset($additional_css)) echo $additional_css; ?>
    </style>
    
    <!-- CSS اضافی برای صفحه خاص -->
    <?php if (isset($page_css)) echo $page_css; ?>
</head>
<body>

<!-- Sidebar -->
<nav class="admin-sidebar">
    <div class="sidebar-brand">
        <img src="<?php echo BASE_URL; ?>/logo-site.png" alt="CV123" width="50" height="50">
        <h5>پنل مدیریت</h5>
        <small>سیستم مدیریت CV123</small>
    </div>
    
    <div class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'dashboard' ? 'active' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>/admin">
                    <i class="bi bi-speedometer2"></i> داشبورد
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'applicants' ? 'active' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>/admin/applicants">
                    <i class="bi bi-people"></i> متقاضیان
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'interviews' ? 'active' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>/admin/interviews">
                    <i class="bi bi-person-check"></i> درخواست‌ها
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'users' ? 'active' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>/admin/users">
                    <i class="bi bi-person-gear"></i> کاربران
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'content' ? 'active' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>/admin/content">
                    <i class="bi bi-file-earmark-text"></i> مدیریت محتوا
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'settings' ? 'active' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>/admin/settings">
                    <i class="bi bi-gear"></i> تنظیمات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page ?? '') === 'logs' ? 'active' : ''; ?>" 
                   href="<?php echo BASE_URL; ?>/admin/logs">
                    <i class="bi bi-file-text"></i> مدیریت لاگ‌ها
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-danger" href="<?php echo BASE_URL; ?>/admin/logout">
                    <i class="bi bi-box-arrow-left"></i> خروج
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<main class="admin-main">
    <!-- Header -->
    <header class="admin-header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h1><?php echo $page_title ?? 'پنل مدیریت'; ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin">داشبورد</a></li>
                    <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                        <?php foreach ($breadcrumbs as $breadcrumb): ?>
                            <li class="breadcrumb-item active"><?php echo $breadcrumb; ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
    </header>
    
    <!-- Content Area -->
    <div class="admin-content">
        <?php
        // نمایش محتوای صفحه
        if (isset($content_file) && file_exists($content_file)) {
            include $content_file;
        } else {
            echo "محتوای صفحه یافت نشد.";
        }
        ?>
    </div>
</main>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript اضافی برای صفحه خاص -->
<?php if (isset($page_js)) echo $page_js; ?>

</body>
</html>
