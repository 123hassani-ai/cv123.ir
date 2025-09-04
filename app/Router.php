<?php
/**
 * فایل: /app/Router.php
 * توضیحات: کلاس مدیریت مسیرهای پروژه
 */

class Router
{
    private $routes = [];
    private static $instance = null;

    /**
     * گرفتن نمونه واحد از روتر
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * اضافه کردن مسیر GET
     */
    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
        return $this;
    }

    /**
     * اضافه کردن مسیر POST
     */
    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
        return $this;
    }

    /**
     * اضافه کردن مسیر به لیست مسیرها
     */
    private function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    /**
     * اجرای روتر و مدیریت درخواست
     */
    public function run()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        
        // حذف مسیر پایه از URI
        if (!empty(BASE_URL)) {
            $uri = str_replace(BASE_URL, '', $uri);
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $pattern = $this->convertPathToPattern($route['path']);
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // حذف کل URI از نتایج
                
                if (is_callable($route['callback'])) {
                    return call_user_func_array($route['callback'], $matches);
                } else {
                    list($controller, $action) = explode('@', $route['callback']);
                    $controllerFile = APP_PATH . '/controllers/' . $controller . '.php';
                    
                    if (file_exists($controllerFile)) {
                        require_once $controllerFile;
                        $controllerInstance = new $controller();
                        return call_user_func_array([$controllerInstance, $action], $matches);
                    }
                }
            }
        }
        
        // مسیر پیدا نشد
        $this->notFound();
    }

    /**
     * تبدیل مسیر به الگوی Regex
     */
    private function convertPathToPattern($path)
    {
        $pattern = str_replace('/', '\/', $path);
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^\/]+)', $pattern);
        return '/^' . $pattern . '$/';
    }

    /**
     * نمایش صفحه ۴۰۴
     */
    private function notFound()
    {
        header("HTTP/1.0 404 Not Found");
        include APP_PATH . '/views/404.php';
        exit;
    }
}

// تعریف مسیرهای پروژه
$router = Router::getInstance();

// مسیرهای کاربر
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// مسیرهای مدیر
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/login', 'AdminController@showLogin');
$router->post('/admin/login', 'AdminController@login');

// مسیرهای API
$router->post('/api/chat', 'ChatController@sendMessage');

// اجرای روتر
$router->run();
?>
