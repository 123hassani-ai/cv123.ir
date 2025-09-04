<?php
/**
 * فایل: /app/views/home.php
 * توضیحات: نمای صفحه اصلی سایت
 */
?>

<div class="row">
    <div class="col-md-12 text-center mb-5">
        <img src="<?php echo BASE_URL; ?>/logo-site.png" alt="لوگوی مجتمع کامپیوتر یک دو سه" class="img-fluid" style="max-height: 150px;">
        <h1 class="mt-3">مجتمع کامپیوتر یک دو سه</h1>
        <p class="lead">سامانه استخدام نیروی انسانی</p>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card h-100 shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">ثبت‌نام متقاضیان</h5>
            </div>
            <div class="card-body">
                <p class="card-text">اگر به دنبال موقعیت‌های شغلی در مجتمع کامپیوتر یک دو سه هستید، رزومه خود را ثبت کنید.</p>
                <a href="<?php echo BASE_URL; ?>/register" class="btn btn-outline-primary">ثبت‌نام و ارسال رزومه</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-success">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">ورود به حساب کاربری</h5>
            </div>
            <div class="card-body">
                <p class="card-text">اگر قبلاً ثبت‌نام کرده‌اید، می‌توانید وارد حساب کاربری خود شوید و وضعیت درخواست خود را پیگیری کنید.</p>
                <a href="<?php echo BASE_URL; ?>/login" class="btn btn-outline-success">ورود به حساب کاربری</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">موقعیت‌های شغلی فعال</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">برنامه‌نویس PHP</h5>
                                <p class="card-text">نیازمند برنامه‌نویس PHP با حداقل ۲ سال سابقه کار</p>
                                <a href="<?php echo BASE_URL; ?>/job/1" class="btn btn-sm btn-primary">مشاهده جزئیات</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">طراح رابط کاربری</h5>
                                <p class="card-text">نیازمند طراح UI/UX با تسلط بر Adobe XD و Figma</p>
                                <a href="<?php echo BASE_URL; ?>/job/2" class="btn btn-sm btn-primary">مشاهده جزئیات</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">پشتیبان فنی</h5>
                                <p class="card-text">نیازمند کارشناس پشتیبانی با روابط عمومی بالا</p>
                                <a href="<?php echo BASE_URL; ?>/job/3" class="btn btn-sm btn-primary">مشاهده جزئیات</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
