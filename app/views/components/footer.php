<?php
/**
 * فایل: /app/views/components/footer.php
 * توضیحات: فوتر سایت
 */
?>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h5>مجتمع کامپیوتر یک دو سه</h5>
                <p class="text-muted">سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه</p>
                <p class="mt-3">
                    <a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-white">
                        <img src="<?php echo BASE_URL; ?>/logo-site.png" alt="لوگو" height="40" class="d-inline-block">
                    </a>
                </p>
            </div>
            
            <div class="col-md-4 mb-4 mb-md-0">
                <h5>دسترسی سریع</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-muted">صفحه اصلی</a></li>
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>/jobs" class="text-decoration-none text-muted">فرصت‌های شغلی</a></li>
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>/about" class="text-decoration-none text-muted">درباره ما</a></li>
                    <li class="mb-2"><a href="<?php echo BASE_URL; ?>/contact" class="text-decoration-none text-muted">تماس با ما</a></li>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h5>تماس با ما</h5>
                <address class="text-muted">
                    <p class="mb-2">آدرس: تهران، خیابان آزادی، مجتمع کامپیوتر یک دو سه</p>
                    <p class="mb-2">تلفن: ۰۲۱-۱۲۳۴۵۶۷۸</p>
                    <p class="mb-2">ایمیل: info@cv123.ir</p>
                </address>
                <div class="mt-3">
                    <a href="#" class="text-decoration-none text-muted me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-decoration-none text-muted me-3"><i class="bi bi-telegram"></i></a>
                    <a href="#" class="text-decoration-none text-muted me-3"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="text-decoration-none text-muted"><i class="bi bi-twitter"></i></a>
                </div>
            </div>
        </div>
        
        <hr class="my-4 bg-secondary">
        
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-muted">&copy; ۱۴۰۴ مجتمع کامپیوتر یک دو سه. تمامی حقوق محفوظ است.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 text-muted">طراحی و توسعه: تیم برنامه‌نویسی cv123.ir</p>
            </div>
        </div>
    </div>
</footer>
