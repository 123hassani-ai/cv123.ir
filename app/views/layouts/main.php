<?php
/**
 * فایل: /app/views/layouts/main.php
 * توضیحات: قالب اصلی سایت
 */
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo APP_DESCRIPTION; ?>">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . APP_NAME : APP_NAME; ?></title>
    
    <!-- فونت وزیرمتن -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/fonts/vazirmatn/vazirmatn.css">
    
    <!-- بوت استرپ ۵ -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/bootstrap.rtl.min.css">
    
    <!-- استایل‌های اختصاصی -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    
    <!-- منیفست PWA -->
    <link rel="manifest" href="<?php echo BASE_URL; ?>/manifest.json">
    
    <!-- آیکون‌ها -->
    <link rel="icon" href="<?php echo BASE_URL; ?>/public/images/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/public/images/logo-site-dark.png">
    
    <?php if (isset($extraStyles)) echo $extraStyles; ?>
</head>
<body>
    <!-- هدر -->
    <?php include_once APP_PATH . '/views/components/header.php'; ?>
    
    <!-- محتوای اصلی -->
    <main class="container py-4">
        <?php include_once $viewPath; ?>
    </main>
    
    <!-- فوتر -->
    <?php include_once APP_PATH . '/views/components/footer.php'; ?>
    
    <!-- جاوااسکریپت‌ها -->
    <script src="<?php echo BASE_URL; ?>/public/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/public/js/app.js"></script>
    
    <!-- سرویس ورکر PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?php echo BASE_URL; ?>/service-worker.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed: ', error);
                    });
            });
        }
    </script>
    
    <?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
