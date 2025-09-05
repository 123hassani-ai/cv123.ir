<?php
/**
 * فایل: /app/views/auth/register.php
 * توضیحات: نمای صفحه ثبت‌نام کاربران
 */
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">ثبت‌نام و ارسال رزومه</h5>
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
                
                <form action="<?php echo BASE_URL; ?>/register" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">نام و نام خانوادگی</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">ایمیل</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">شماره تلفن</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">رمز عبور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resume" class="form-label">آپلود رزومه (PDF، DOC یا DOCX)</label>
                        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx">
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">با <a href="#">قوانین و مقررات</a> سایت موافقم</label>
                    </div>
                    
                    <button type="submit" class="btn btn-success">ثبت‌نام</button>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <p>قبلاً ثبت‌نام کرده‌اید؟ <a href="<?php echo BASE_URL; ?>/login">وارد شوید</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
