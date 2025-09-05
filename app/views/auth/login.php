<?php
/**
 * فایل: /app/views/auth/login.php
 * توضیحات: نمای صفحه ورود کاربران
 */
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">ورود به حساب کاربری</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info">
                        <?php 
                        echo $_SESSION['message']; 
                        unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo BASE_URL; ?>/login" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">ایمیل</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
                    </div>
                    <button type="submit" class="btn btn-primary">ورود</button>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <p>حساب کاربری ندارید؟ <a href="<?php echo BASE_URL; ?>/register">ثبت‌نام کنید</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
