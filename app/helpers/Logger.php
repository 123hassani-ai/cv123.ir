<?php
/**
 * فایل: /app/helpers/Logger.php
 * توضیحات: سیستم لاگ‌گیری هوشمند CV123
 * نحوه استفاده: Logger::info('پیام فارسی', 'English message', ['context' => 'data']);
 */

class Logger
{
    // سطوح لاگ
    const LEVEL_SUCCESS = 'SUCCESS';
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_DEBUG = 'DEBUG';
    
    // دسته‌بندی‌ها
    const CATEGORY_DATABASE = 'DATABASE';
    const CATEGORY_API = 'API';
    const CATEGORY_SYSTEM = 'SYSTEM';
    const CATEGORY_AUTH = 'AUTH';
    const CATEGORY_SMS = 'SMS';
    const CATEGORY_AI = 'AI';
    const CATEGORY_USER = 'USER';
    
    private static $settings = null;
    private static $currentLogFile = null;
    
    /**
     * دریافت تنظیمات لاگ‌گیری (ثابت و بدون دیتابیس)
     */
    private static function getSettings()
    {
        if (self::$settings === null) {
            // تنظیمات ثابت و سخت‌کد شده برای جلوگیری از هر مشکلی
            self::$settings = [
                'log_enabled' => true,
                'log_max_records_per_file' => 50,     // کاهش بیشتر
                'log_max_file_size_mb' => 0.5,        // 500KB
                'log_retention_days' => 7,            // کاهش مدت نگهداری 
                'log_debug_mode' => false
            ];
        }
        
        return self::$settings;
    }

    /**
     * دریافت فایل لاگ فعلی (ساده شده)
     */
    private static function getCurrentLogFile()
    {
        if (self::$currentLogFile !== null) {
            return self::$currentLogFile;
        }
        
        $logsDir = dirname(__DIR__, 2) . '/logs';
        
        // ایجاد پوشه logs در صورت عدم وجود
        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }
        
        $date = date('Y-m-d');
        $filename = "app_{$date}_001.json";
        $filepath = $logsDir . '/' . $filename;
        
        self::$currentLogFile = $filepath;
        return $filepath;
    }

    /**
     * ثبت لاگ
     */
    private static function log($level, $category, $messageFa, $messageEn, $context = [])
    {
        $settings = self::getSettings();
        
        // اگر لاگ‌گیری غیرفعال است
        if (!$settings['log_enabled']) {
            return;
        }
        
        // اگر DEBUG غیرفعال است و سطح DEBUG است
        if ($level === self::LEVEL_DEBUG && !$settings['log_debug_mode']) {
            return;
        }
        
        try {
            // ساخت رکورد لاگ فوق‌العاده مینیمال
            $logEntry = [
                'id' => uniqid(),
                'time' => date('H:i:s'),
                'lv' => $level,
                'cat' => $category,
                'msg' => mb_substr($messageFa, 0, 50), // کاهش از 100 به 50
                'user' => $_SESSION['username'] ?? $_SESSION['admin_username'] ?? 'guest',
                'file' => basename(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['file'] ?? 'unknown')
            ];
            
            // context فقط اگر خیلی کوچک باشد
            if (!empty($context) && is_array($context) && count($context) <= 2) {
                $cleanContext = [];
                foreach ($context as $key => $value) {
                    if (is_scalar($value) && strlen($value) < 50) { // کاهش از 200 به 50
                        $cleanContext[substr($key, 0, 10)] = substr($value, 0, 50); // کلید و مقدار کوتاه
                    }
                }
                if (!empty($cleanContext)) {
                    $logEntry['ctx'] = $cleanContext;
                }
            }
            
            // ذخیره لاگ در فایل
            self::writeToFile($logEntry);
            
            // پاکسازی گاه‌به‌گاه
            if (rand(1, 100) === 1) {
                self::cleanupOldLogs();
            }
            
        } catch (Exception $e) {
            // در صورت خطا در سیستم لاگ، آن را در error_log ثبت کن
            error_log("Logger error: " . $e->getMessage());
        }
    }
    
    /**
     * نوشتن لاگ در فایل (فوق‌العاده ساده شده)
     */
    private static function writeToFile($logEntry)
    {
        try {
            $logFile = self::getCurrentLogFile();
            
            // فقط append کردن به فایل به صورت خط
            $logLine = json_encode($logEntry, JSON_UNESCAPED_UNICODE) . "
";
            file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
            
        } catch (Exception $e) {
            error_log("Logger simple write error: " . $e->getMessage());
        }
    }    /**
     * اضافه کردن لاگ به فایل JSON بزرگ بدون بارگذاری کامل
     */
    private static function appendToLargeJsonFile($filePath, $logEntry)
    {
        $fp = fopen($filePath, 'r+');
        if (!$fp) {
            throw new Exception("Cannot open log file for writing");
        }
        
        if (flock($fp, LOCK_EX)) {
            // رفتن به انتهای فایل
            fseek($fp, -2, SEEK_END); // 2 کاراکتر از انتها (]\n)
            
            // خواندن 2 کاراکتر آخر برای اطمینان
            $lastChars = fread($fp, 2);
            
            // اگر فایل به درستی با ] تمام می‌شود
            if (trim($lastChars) === ']') {
                // بازگشت به موقعیت قبل از ]
                fseek($fp, -1, SEEK_END);
                
                // اضافه کردن , اگر فایل خالی نیست
                fseek($fp, 2, SEEK_SET);
                $hasContent = trim(fread($fp, 10)) !== ']';
                
                fseek($fp, -1, SEEK_END);
                
                if ($hasContent) {
                    fwrite($fp, ',');
                }
                
                // اضافه کردن رکورد جدید (فرمت compact)
                fwrite($fp, json_encode($logEntry, JSON_UNESCAPED_UNICODE));
                fwrite($fp, "]");
            } else {
                // فایل خراب است، بازسازی کن
                fseek($fp, 0, SEEK_END);
                fwrite($fp, "\n]");
            }
            
            flock($fp, LOCK_UN);
        }
        
        fclose($fp);
    }
    
    /**
     * پیدا کردن فراخواننده واقعی
     */
    private static function findRealCaller($trace)
    {
        foreach ($trace as $item) {
            if (isset($item['file']) && 
                basename($item['file']) !== 'Logger.php' && 
                basename($item['file']) !== 'Database.php') {
                return [
                    'file' => basename($item['file']),
                    'line' => $item['line'] ?? null,
                    'function' => $item['function'] ?? null
                ];
            }
        }
        
        return ['file' => 'unknown', 'line' => null, 'function' => null];
    }
    
    /**
     * دریافت IP واقعی
     */
    private static function getRealIpAddress()
    {
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                return trim($ips[0]);
            }
        }
        
        return '127.0.0.1';
    }
    
    /**
     * پاکسازی لاگ‌های قدیمی (ساده شده)
     */
    private static function cleanupOldLogs()
    {
        try {
            $settings = self::getSettings();
            $logsDir = dirname(__DIR__, 2) . '/logs';
            $cutoffDate = strtotime("-{$settings['log_retention_days']} days");
            
            $files = glob($logsDir . '/app_*.json');
            foreach ($files as $file) {
                if (filemtime($file) < $cutoffDate) {
                    unlink($file);
                }
            }
        } catch (Exception $e) {
            error_log("Cleanup error: " . $e->getMessage());
        }
    }
    
    // متدهای عمومی برای ثبت لاگ
    
    /**
     * ثبت لاگ موفقیت
     */
    public static function success($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_SUCCESS, self::CATEGORY_SYSTEM, $messageFa, $messageEn, $context);
    }
    
    /**
     * ثبت لاگ اطلاعات
     */
    public static function info($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_INFO, self::CATEGORY_SYSTEM, $messageFa, $messageEn, $context);
    }
    
    /**
     * ثبت لاگ هشدار
     */
    public static function warning($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_WARNING, self::CATEGORY_SYSTEM, $messageFa, $messageEn, $context);
    }
    
    /**
     * ثبت لاگ خطا
     */
    public static function error($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_ERROR, self::CATEGORY_SYSTEM, $messageFa, $messageEn, $context);
    }
    
    /**
     * ثبت لاگ دیباگ
     */
    public static function debug($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_DEBUG, self::CATEGORY_SYSTEM, $messageFa, $messageEn, $context);
    }
    
    // متدهای تخصصی
    
    /**
     * لاگ عملیات دیتابیس
     */
    public static function database($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_INFO, self::CATEGORY_DATABASE, $messageFa, $messageEn, $context);
    }
    
    /**
     * لاگ فراخوانی API
     */
    public static function api($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_INFO, self::CATEGORY_API, $messageFa, $messageEn, $context);
    }
    
    /**
     * لاگ احراز هویت
     */
    public static function auth($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_INFO, self::CATEGORY_AUTH, $messageFa, $messageEn, $context);
    }
    
    /**
     * لاگ عملیات پیامک
     */
    public static function sms($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_INFO, self::CATEGORY_SMS, $messageFa, $messageEn, $context);
    }
    
    /**
     * لاگ هوش مصنوعی
     */
    public static function ai($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_INFO, self::CATEGORY_AI, $messageFa, $messageEn, $context);
    }
    
    /**
     * لاگ فعالیت کاربر
     */
    public static function user($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_INFO, self::CATEGORY_USER, $messageFa, $messageEn, $context);
    }
    
    // متدهای خطا
    
    /**
     * خطای دیتابیس
     */
    public static function databaseError($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_ERROR, self::CATEGORY_DATABASE, $messageFa, $messageEn, $context);
    }
    
    /**
     * خطای API
     */
    public static function apiError($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_ERROR, self::CATEGORY_API, $messageFa, $messageEn, $context);
    }
    
    /**
     * خطای احراز هویت
     */
    public static function authError($messageFa, $messageEn = '', $context = [])
    {
        self::log(self::LEVEL_ERROR, self::CATEGORY_AUTH, $messageFa, $messageEn, $context);
    }

    /**
     * دریافت آمار ساده لاگ‌ها برای پنل مدیریت
     */
    /**
     * دریافت آمار ساده لاگ‌ها برای پنل مدیریت
     */
    public static function getStats()
    {
        try {
            $logsDir = dirname(__DIR__, 2) . '/logs';
            $files = glob($logsDir . '/simple_*.json');
            
            $totalLogs = 0;
            $totalSize = 0;
            $errorCount = 0;
            
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $size = filesize($file);
                    $totalSize += $size;
                    
                    // شمارش سریع لاگ‌ها
                    $content = file_get_contents($file);
                    if ($content) {
                        $lines = explode("\n", trim($content));
                        $totalLogs += count(array_filter($lines));
                        
                        // شمارش خطاها
                        $errorCount += substr_count($content, '"lv":"ERROR"');
                    }
                }
            }
            
            return [
                'total_files' => count($files),
                'total_logs' => $totalLogs,
                'total_size_mb' => round($totalSize / (1024 * 1024), 2),
                'error_count' => $errorCount
            ];
        } catch (Exception $e) {
            return [
                'total_files' => 0,
                'total_logs' => 0,
                'total_size_mb' => 0,
                'error_count' => 0
            ];
        }
    }

    /**
     * دریافت لیست فایل‌های لاگ ساده برای پنل مدیریت  
     */
    public static function getLogFiles()
    {
        try {
            $logsDir = dirname(__DIR__, 2) . '/logs';
            $files = glob($logsDir . '/simple_*.json');
            
            $fileList = [];
            foreach ($files as $file) {
                $fileList[] = [
                    'filename' => basename($file),
                    'size' => filesize($file),
                    'size_kb' => round(filesize($file) / 1024, 1),
                    'created_at' => date('Y-m-d H:i:s', filectime($file))
                ];
            }
            
            return $fileList;
        } catch (Exception $e) {
            return [];
        }
    }
}
?>
