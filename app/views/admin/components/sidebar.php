<?php
/**
 * فایل: /app/views/admin/components/sidebar.php
 * توضیحات: منوی کناری پنل مدیریت
 */
?>

<div class="admin-sidebar bg-dark text-white p-3">
    <div class="d-flex align-items-center mb-4">
        <img src="<?php echo BASE_URL; ?>/public/images/logo-site-light.png" alt="لوگو" class="me-2" height="40">
        <h5 class="mb-0">پنل مدیریت</h5>
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white <?php echo strpos($_SERVER['REQUEST_URI'], '/admin') !== false && strpos($_SERVER['REQUEST_URI'], '/admin/') === false ? 'active bg-primary' : ''; ?>" href="<?php echo BASE_URL; ?>/admin">
                <i class="bi bi-speedometer2 me-2"></i> داشبورد
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-white <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/jobs') !== false ? 'active bg-primary' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/jobs">
                <i class="bi bi-briefcase me-2"></i> مدیریت مشاغل
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-white <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/applications') !== false ? 'active bg-primary' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/applications">
                <i class="bi bi-file-earmark-text me-2"></i> درخواست‌ها
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-white <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active bg-primary' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/users">
                <i class="bi bi-people me-2"></i> کاربران
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-white <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/content') !== false ? 'active bg-primary' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/content">
                <i class="bi bi-file-earmark-richtext me-2"></i> مدیریت محتوا
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-white <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'active bg-primary' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/settings">
                <i class="bi bi-gear me-2"></i> تنظیمات
            </a>
        </li>
        <li class="nav-item mt-2">
            <a class="nav-link text-white <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/logs') !== false ? 'active bg-primary' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/logs">
                <i class="bi bi-journal-text me-2"></i> مدیریت لاگ‌ها
            </a>
        </li>
        <li class="nav-item mt-4">
            <a class="nav-link text-white bg-danger" href="<?php echo BASE_URL; ?>/logout">
                <i class="bi bi-box-arrow-right me-2"></i> خروج
            </a>
        </li>
    </ul>
</div>
