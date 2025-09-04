<?php
/**
 * فایل: /app/helpers/Database.php
 * توضیحات: کلاس ارتباط با پایگاه داده
 */

class Database
{
    private static $instance = null;
    private $connection;
    private $statement;

    /**
     * سازنده خصوصی برای الگوی Singleton
     */
    private function __construct()
    {
        try {
            $this->connection = new PDO(
                'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            die('خطا در اتصال به پایگاه داده. لطفاً با مدیر سایت تماس بگیرید.');
        }
    }

    /**
     * گرفتن نمونه واحد از کلاس Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * اجرای کوئری SQL
     */
    public function query($sql, $params = [])
    {
        try {
            $this->statement = $this->connection->prepare($sql);
            $this->statement->execute($params);
            return $this;
        } catch (PDOException $e) {
            $this->logError($e->getMessage(), $sql, $params);
            throw $e;
        }
    }

    /**
     * دریافت همه نتایج
     */
    public function fetchAll()
    {
        return $this->statement->fetchAll();
    }

    /**
     * دریافت یک نتیجه
     */
    public function fetch()
    {
        return $this->statement->fetch();
    }

    /**
     * دریافت تعداد نتایج
     */
    public function rowCount()
    {
        return $this->statement->rowCount();
    }

    /**
     * دریافت آخرین شناسه درج شده
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * شروع تراکنش
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * تایید تراکنش
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * برگشت تراکنش
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    /**
     * ثبت خطاها در فایل لاگ
     */
    private function logError($message, $sql = null, $params = [])
    {
        $logMessage = date('Y-m-d H:i:s') . ' - Error: ' . $message;
        
        if ($sql) {
            $logMessage .= ' - SQL: ' . $sql;
        }
        
        if (!empty($params)) {
            $logMessage .= ' - Params: ' . json_encode($params);
        }
        
        error_log($logMessage . PHP_EOL, 3, ROOT_PATH . '/logs/database_errors.log');
    }
}
?>
