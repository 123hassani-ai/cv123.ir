<?php

/**
 * کلاس لاگ‌گیری فوق‌العاده ساده و بهینه
 */
class SimpleLogger
{
    private static $logFile = null;

    /**
     * ثبت لاگ ساده
     */
    public static function log($level, $message)
    {
        try {
            $logEntry = [
                'time' => date('H:i:s'),
                'level' => $level,
                'msg' => mb_substr($message, 0, 100),
                'file' => basename(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['file'] ?? 'unknown')
            ];
            
            self::writeToFile($logEntry);
            
        } catch (Exception $e) {
            error_log("SimpleLogger error: " . $e->getMessage());
        }
    }
    
    /**
     * نوشتن در فایل
     */
    private static function writeToFile($logEntry)
    {
        if (self::$logFile === null) {
            $date = date('Y-m-d');
            self::$logFile = "logs/simple_{$date}.json";
        }
        
        // اگر فایل وجود ندارد
        if (!file_exists(self::$logFile)) {
            file_put_contents(self::$logFile, json_encode([], JSON_UNESCAPED_UNICODE));
        }
        
        // خواندن و اضافه کردن
        $content = file_get_contents(self::$logFile);
        $logs = json_decode($content, true) ?: [];
        
        // محدود کردن به 20 رکورد
        if (count($logs) >= 20) {
            $logs = array_slice($logs, -10); // نگه داشتن 10 آخری
        }
        
        $logs[] = $logEntry;
        
        file_put_contents(self::$logFile, json_encode($logs, JSON_UNESCAPED_UNICODE));
    }
    
    public static function info($message) { self::log('INFO', $message); }
    public static function error($message) { self::log('ERROR', $message); }
}
