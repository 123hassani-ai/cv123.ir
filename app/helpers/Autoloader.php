<?php
/**
 * فایل: /app/helpers/Autoloader.php
 * توضیحات: کلاس بارگذاری خودکار کلاس‌های پروژه
 */

class Autoloader
{
    /**
     * ثبت اتولودر در استک اتولودرهای PHP
     */
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            
            // مسیرهای ممکن برای جستجوی کلاس
            $paths = [
                __DIR__ . '/../models/',
                __DIR__ . '/../controllers/', 
                __DIR__ . '/../helpers/',
                __DIR__ . '/../middleware/'
            ];
            
            foreach ($paths as $path) {
                $file = $path . $class . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return true;
                }
            }
            
            return false;
        });
    }
}

// ثبت اتولودر
Autoloader::register();
?>
