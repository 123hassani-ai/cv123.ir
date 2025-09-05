<?php
/**
 * فایل: /app/helpers/Database.php
 * توضیحات: کلاس مدیریت پایگاه داده با پشتیبانی از لاگ‌گیری
 */

class Database
{
    private static $instance = null;
    private $connection = null;
    private $statement = null;

    /**
     * سازنده خصوصی برای الگوی Singleton
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * اتصال به پایگاه داده
     */
    private function connect()
    {
        try {
            // تشخیص محیط XAMPP در macOS و استفاده از Unix Socket
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            
            // تنظیمات اتصال
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            // ثبت خطا بدون استفاده از Logger تا از حلقه بی‌نهایت جلوگیری شود
            error_log("Database connection error: " . $e->getMessage());
            throw new Exception("خطا در اتصال به پایگاه داده: " . $e->getMessage());
        }
    }

    /**
     * دریافت نمونه واحد کلاس
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * اجرای کوئری
     */
    public function query($sql, $params = [])
    {
        $startTime = microtime(true);
        $tableName = $this->extractTableName($sql);
        $action = $this->extractAction($sql);
        
        try {
            $this->statement = $this->connection->prepare($sql);
            $this->statement->execute($params);
            
            $executionTime = microtime(true) - $startTime;
            
            // ثبت لاگ موفق (اگر Logger موجود باشد)
            if (class_exists('Logger')) {
                Logger::database(
                    "کوئری $action روی جدول $tableName اجرا شد",
                    "$action query executed on $tableName table",
                    [
                        'query' => $sql,
                        'params' => $params,
                        'execution_time' => round($executionTime, 4),
                        'affected_rows' => $this->statement->rowCount()
                    ]
                );
            }
            
            return $this;
        } catch (PDOException $e) {
            $executionTime = microtime(true) - $startTime;
            
            // ثبت خطای دیتابیس (اگر Logger موجود باشد)
            if (class_exists('Logger')) {
                Logger::databaseError(
                    "خطا در اجرای کوئری $action روی جدول $tableName",
                    "$action query error on $tableName table",
                    [
                        'query' => $sql,
                        'params' => $params,
                        'execution_time' => round($executionTime, 4),
                        'error_message' => $e->getMessage(),
                        'error_code' => $e->getCode()
                    ]
                );
            }
            
            // ثبت در error_log سیستم
            error_log("Database query error: " . $e->getMessage() . " | Query: " . $sql);
            
            throw new Exception("خطا در اجرای کوئری: " . $e->getMessage());
        }
    }

    /**
     * دریافت تمام نتایج
     */
    public function fetchAll()
    {
        return $this->statement ? $this->statement->fetchAll() : [];
    }

    /**
     * دریافت یک نتیجه
     */
    public function fetch()
    {
        return $this->statement ? $this->statement->fetch() : null;
    }

    /**
     * دریافت تعداد ردیف‌های تاثیرگرفته
     */
    public function rowCount()
    {
        return $this->statement ? $this->statement->rowCount() : 0;
    }

    /**
     * شروع تراکنش
     */
    public function beginTransaction()
    {
        try {
            $result = $this->connection->beginTransaction();
            
            // ثبت لاگ تراکنش
            if (class_exists('Logger')) {
                Logger::database('شروع تراکنش', 'Transaction started');
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Begin transaction error: " . $e->getMessage());
            throw new Exception("خطا در شروع تراکنش: " . $e->getMessage());
        }
    }

    /**
     * تایید تراکنش
     */
    public function commit()
    {
        try {
            $result = $this->connection->commit();
            
            // ثبت لاگ تراکنش
            if (class_exists('Logger')) {
                Logger::database('تایید تراکنش', 'Transaction committed');
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Commit transaction error: " . $e->getMessage());
            throw new Exception("خطا در تایید تراکنش: " . $e->getMessage());
        }
    }

    /**
     * لغو تراکنش
     */
    public function rollBack()
    {
        try {
            $result = $this->connection->rollBack();
            
            // ثبت لاگ تراکنش
            if (class_exists('Logger')) {
                Logger::database('لغو تراکنش', 'Transaction rolled back');
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Rollback transaction error: " . $e->getMessage());
            throw new Exception("خطا در لغو تراکنش: " . $e->getMessage());
        }
    }

    /**
     * بررسی وضعیت تراکنش
     */
    public function inTransaction()
    {
        return $this->connection ? $this->connection->inTransaction() : false;
    }

    /**
     * استخراج نام جدول از کوئری
     */
    private function extractTableName($sql)
    {
        $sql = trim($sql);
        $patterns = [
            '/INSERT\s+INTO\s+`?(\w+)`?/i',
            '/UPDATE\s+`?(\w+)`?/i',
            '/DELETE\s+FROM\s+`?(\w+)`?/i',
            '/SELECT.*FROM\s+`?(\w+)`?/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $sql, $matches)) {
                return $matches[1];
            }
        }
        
        return 'unknown';
    }

    /**
     * استخراج نوع عملیات از کوئری
     */
    private function extractAction($sql)
    {
        $sql = trim(strtoupper($sql));
        
        if (strpos($sql, 'SELECT') === 0) return 'SELECT';
        if (strpos($sql, 'INSERT') === 0) return 'INSERT';
        if (strpos($sql, 'UPDATE') === 0) return 'UPDATE';
        if (strpos($sql, 'DELETE') === 0) return 'DELETE';
        if (strpos($sql, 'CREATE') === 0) return 'CREATE';
        if (strpos($sql, 'ALTER') === 0) return 'ALTER';
        if (strpos($sql, 'DROP') === 0) return 'DROP';
        
        return 'UNKNOWN';
    }

    /**
     * دریافت آخرین ID درج شده
     */
    public function lastInsertId()
    {
        return $this->connection ? $this->connection->lastInsertId() : null;
    }

    /**
     * بستن اتصال
     */
    public function __destruct()
    {
        $this->statement = null;
        $this->connection = null;
    }
}
