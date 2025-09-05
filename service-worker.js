/**
 * فایل: /service-worker.js
 * توضیحات: سرویس ورکر برای قابلیت PWA
 */

const CACHE_NAME = 'cv123-cache-v1';
const ASSETS = [
    '/',
    '/index.php',
    '/manifest.json',
    '/public/css/style.css',
    '/public/css/bootstrap.rtl.min.css',
    '/public/js/app.js',
    '/public/js/bootstrap.bundle.min.js',
    '/public/js/jquery-3.6.0.min.js',
    '/public/fonts/vazirmatn/vazirmatn.css',
    '/public/fonts/vazirmatn/Vazirmatn-Regular.woff2',
    '/public/fonts/vazirmatn/Vazirmatn-Bold.woff2',
    '/public/images/icons/icon-72x72.png',
    '/public/images/icons/icon-96x96.png',
    '/public/images/icons/icon-128x128.png',
    '/public/images/icons/icon-144x144.png',
    '/public/images/icons/icon-152x152.png',
    '/public/images/icons/icon-192x192.png',
    '/public/images/icons/icon-384x384.png',
    '/public/images/icons/icon-512x512.png',
    '/logo-site.png',
    '/favicon.ico'
];

// نصب سرویس ورکر و ذخیره فایل‌های ضروری در کش
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(ASSETS);
            })
    );
});

// فعال کردن سرویس ورکر و حذف کش‌های قدیمی
self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// استراتژی کش اول، سپس شبکه
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // اگر در کش موجود باشد، آن را برگردان
                if (response) {
                    return response;
                }
                
                // در غیر این صورت، درخواست را به شبکه بفرست
                return fetch(event.request).then(
                    response => {
                        // اگر درخواست یا پاسخ قابل ذخیره‌سازی نباشد، فقط پاسخ را برگردان
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }
                        
                        // کپی پاسخ برای کش کردن
                        let responseToCache = response.clone();
                        
                        caches.open(CACHE_NAME)
                            .then(cache => {
                                cache.put(event.request, responseToCache);
                            });
                            
                        return response;
                    }
                );
            })
            .catch(() => {
                // در صورت بروز خطا یا عدم دسترسی به اینترنت، صفحه آفلاین را نمایش بده
                if (event.request.mode === 'navigate') {
                    return caches.match('/offline.html');
                }
            })
    );
});

// پیام‌های از کلاینت
self.addEventListener('message', event => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
