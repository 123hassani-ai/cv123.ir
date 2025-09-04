<?php
/**
 * فایل: /app/views/404.php
 * توضیحات: صفحه خطای ۴۰۴
 */

$pageTitle = 'صفحه یافت نشد';
?>

<div class="container text-center my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h1 class="display-1 text-danger">404</h1>
                    <h2 class="mb-4">صفحه مورد نظر یافت نشد</h2>
                    <p class="lead mb-4">متأسفانه صفحه‌ای که به دنبال آن هستید وجود ندارد یا به آدرس دیگری منتقل شده است.</p>
                    <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary btn-lg">بازگشت به صفحه اصلی</a>
                </div>
            </div>
        </div>
    </div>
</div>
