<?php
/**
 * کنترلر مدیریت لاگ‌ها
 */
class LogsController
{
    public function index()
    {
        try {
            // دریافت آمار
            $stats = $this->getStatsData();
            
            // تعریف متغیرهای مورد نیاز برای view
            $filters = [
                'search' => $_GET['search'] ?? '',
                'level' => $_GET['level'] ?? '',
                'category' => $_GET['category'] ?? '',
                'date_from' => $_GET['date_from'] ?? '',
                'date_to' => $_GET['date_to'] ?? '',
                'user' => $_GET['user'] ?? '',
                'limit' => 50,
                'page' => 1
            ];

            $logs = $this->getFilteredLogs($filters);
            
            // تنظیم متغیرهای layout
            $page_title = 'مدیریت لاگ‌ها';
            $current_page = 'logs';
            $breadcrumbs = ['مدیریت لاگ‌ها'];
            $content_file = __DIR__ . '/../views/admin/logs_content.php';
            
            // نمایش layout
            require_once __DIR__ . '/../views/admin/layouts/admin_layout.php';
            
        } catch (Exception $e) {
            // در صورت خطا، مقادیر پیش‌فرض
            $stats = [
                'total_logs' => 0,
                'error_count' => 0,
                'total_files' => 0,
                'total_size_mb' => 0
            ];
            
            $filters = [
                'search' => '',
                'level' => '',
                'category' => '',
                'date_from' => '',
                'date_to' => '',
                'user' => ''
            ];
            
            $logs = ['data' => [], 'total' => 0];
            
            // تنظیم متغیرهای layout
            $page_title = 'مدیریت لاگ‌ها - خطا';
            $current_page = 'logs';
            $breadcrumbs = ['مدیریت لاگ‌ها'];
            $content_file = __DIR__ . '/../views/admin/logs_content.php';
            
            // نمایش layout
            require_once __DIR__ . '/../views/admin/layouts/admin_layout.php';
        }
    }
    
    public function ajax()
    {
        try {
            $filters = [
                'search' => $_GET['search'] ?? '',
                'level' => $_GET['level'] ?? '',
                'limit' => 50,
                'page' => 1
            ];
            
            $result = $this->getFilteredLogs($filters);
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function download()
    {
        echo "دانلود فعلاً غیرفعال است";
    }
    
    public function cleanup()
    {
        echo "پاکسازی فعلاً غیرفعال است";
    }
    
    public function getUsersList()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['guest', 'admin'], JSON_UNESCAPED_UNICODE);
    }
    
    private function getStatsData()
    {
        $stats = [
            'total_logs' => 0,
            'error_count' => 0,
            'total_files' => 0,
            'total_size_mb' => 0
        ];
        
        try {
            $logDir = dirname(__DIR__, 2) . '/logs/';
            
            if (is_dir($logDir)) {
                // فایل‌های simple و app
                $simpleFiles = glob($logDir . 'simple_*.json');
                $appFiles = glob($logDir . 'app_*.json');
                $allFiles = array_merge($simpleFiles, $appFiles);
                
                $stats['total_files'] = count($allFiles);
                
                $totalSize = 0;
                $totalLogs = 0;
                $errorCount = 0;
                
                foreach ($allFiles as $file) {
                    if (is_file($file)) {
                        $totalSize += filesize($file);
                        $content = file_get_contents($file);
                        if ($content) {
                            $lines = explode("\n", trim($content));
                            foreach ($lines as $line) {
                                if (!empty(trim($line))) {
                                    $totalLogs++;
                                    $log = json_decode(trim($line), true);
                                    if ($log && isset($log['lv']) && $log['lv'] === 'ERROR') {
                                        $errorCount++;
                                    }
                                }
                            }
                        }
                    }
                }
                
                $stats['total_logs'] = $totalLogs;
                $stats['error_count'] = $errorCount;
                $stats['total_size_mb'] = round($totalSize / (1024 * 1024), 2);
            }
        } catch (Exception $e) {
            // در صورت خطا مقادیر پیش‌فرض برمی‌گردانیم
        }
        
        return $stats;
    }
    
    private function getFilteredLogs($filters)
    {
        try {
            $logsDir = dirname(__DIR__, 2) . '/logs';
            
            // جستجوی فایل‌های لاگ (هر دو فرمت)
            $simpleFiles = glob($logsDir . '/simple_*.json');
            $appFiles = glob($logsDir . '/app_*.json');
            
            $allLogs = [];
            
            // خواندن فایل‌های simple (فرمت جدید)
            foreach ($simpleFiles as $file) {
                $content = file_get_contents($file);
                if ($content) {
                    $lines = explode("\n", trim($content));
                    foreach ($lines as $line) {
                        if (!empty(trim($line))) {
                            $log = json_decode(trim($line), true);
                            if ($log && is_array($log)) {
                                $allLogs[] = $log;
                            }
                        }
                    }
                }
            }
            
            // خواندن فایل‌های app (فرمت قدیمی)
            foreach ($appFiles as $file) {
                $content = file_get_contents($file);
                if ($content) {
                    $lines = explode("\n", trim($content));
                    foreach ($lines as $line) {
                        if (!empty(trim($line))) {
                            $log = json_decode(trim($line), true);
                            if ($log && is_array($log)) {
                                // تطبیق فرمت قدیمی با جدید
                                $formattedLog = [
                                    'id' => $log['id'] ?? '',
                                    'time' => $this->formatDateTime($log['time'] ?? ''),
                                    'lv' => $log['lv'] ?? '',
                                    'cat' => $log['cat'] ?? '',
                                    'msg' => $log['msg'] ?? '',
                                    'msg_en' => '', // فرمت قدیمی انگلیسی ندارد
                                    'user' => $log['user'] ?? 'guest',
                                    'file' => $log['file'] ?? '',
                                    'line' => $log['line'] ?? ''
                                ];
                                $allLogs[] = $formattedLog;
                            }
                        }
                    }
                }
            }
            
            // مرتب‌سازی بر اساس زمان (جدیدترین اول)
            usort($allLogs, function($a, $b) {
                $timeA = $this->parseLogTime($a['time'] ?? '');
                $timeB = $this->parseLogTime($b['time'] ?? '');
                return $timeB - $timeA;
            });
            
            // اعمال فیلترها
            $filteredLogs = $this->applyFilters($allLogs, $filters);
            
            // محدود کردن نتایج
            $limitedLogs = array_slice($filteredLogs, 0, $filters['limit'] ?? 50);
            
            return [
                'data' => $limitedLogs,
                'total' => count($filteredLogs),
                'page' => $filters['page'] ?? 1,
                'limit' => $filters['limit'] ?? 50,
                'pages' => ceil(count($filteredLogs) / ($filters['limit'] ?? 50))
            ];
            
        } catch (Exception $e) {
            return [
                'data' => [],
                'total' => 0,
                'page' => 1,
                'limit' => 50,
                'pages' => 0,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function formatDateTime($time) {
        // اگر فقط ساعت است، تاریخ امروز را اضافه می‌کنیم
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            return date('Y-m-d') . ' ' . $time;
        }
        return $time;
    }
    
    private function parseLogTime($time) {
        $datetime = $this->formatDateTime($time);
        return strtotime($datetime) ?: 0;
    }
    
    private function applyFilters($logs, $filters) {
        $filtered = $logs;
        
        // فیلتر بر اساس سطح
        if (!empty($filters['level'])) {
            $filtered = array_filter($filtered, function($log) use ($filters) {
                return isset($log['lv']) && $log['lv'] === $filters['level'];
            });
        }
        
        // فیلتر بر اساس دسته
        if (!empty($filters['category'])) {
            $filtered = array_filter($filtered, function($log) use ($filters) {
                return isset($log['cat']) && strtolower($log['cat']) === strtolower($filters['category']);
            });
        }
        
        // فیلتر بر اساس جستجو
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $filtered = array_filter($filtered, function($log) use ($search) {
                $msg = strtolower($log['msg'] ?? '');
                $msg_en = strtolower($log['msg_en'] ?? '');
                $file = strtolower($log['file'] ?? '');
                return strpos($msg, $search) !== false || 
                       strpos($msg_en, $search) !== false ||
                       strpos($file, $search) !== false;
            });
        }
        
        return array_values($filtered);
    }
}
?>
