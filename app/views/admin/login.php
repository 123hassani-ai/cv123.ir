<?php
/**
 * فایل: /app/views/admin/login.php
 * توضیحات: نمای صفحه ورود مدیریت
 */
?>

<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-md-5 col-lg-4">
        <div class="text-center mb-4">
            <img src="<?php echo BASE_URL; ?>/public/images/logo-site.png" alt="<?php echo APP_NAME; ?>" style="height: 60px;">
            <h4 class="mt-3 mb-4">ورود به پنل مدیریت</h4>
        </div>
        
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">
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
                
                <form action="<?php echo BASE_URL; ?>/admin/login" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">نام کاربری</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="form-text">برای تست می‌توانید از نام کاربری <strong>admin</strong> استفاده کنید.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">برای تست می‌توانید از رمز عبور <strong>123456</strong> استفاده کنید.</div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">من را به خاطر بسپار</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">ورود به پنل مدیریت</button>
                </form>
                
                <hr>
                
                <div class="text-center mt-3">
                    <p class="mb-0"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none">
                        <i class="bi bi-arrow-right me-1"></i> بازگشت به صفحه اصلی
                    </a></p>
                </div>
            </div>
        </div>
        
        <div class="text-center text-muted mt-4">
            <small>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?> - کلیه حقوق محفوظ است.</small>
        </div>
    </div>
</div>

<style>
body {
    background-color: #f5f7fa;
}
.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
}
</style>
