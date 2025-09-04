# راهنمای توسعه سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه

این مستند، اصول و استانداردهای توسعه پروژه را برای توسعه‌دهندگان شرح می‌دهد.

## معماری پروژه

پروژه بر اساس معماری MVC (Model-View-Controller) طراحی شده است:

- **Model**: کلاس‌های مدل در پوشه `app/models` قرار دارند و مسئول تعامل با پایگاه داده و اعمال منطق تجاری هستند.
- **View**: فایل‌های نما در پوشه `app/views` قرار دارند و مسئول نمایش اطلاعات به کاربر هستند.
- **Controller**: کلاس‌های کنترلر در پوشه `app/controllers` قرار دارند و مسئول دریافت درخواست‌ها، پردازش آن‌ها و فراخوانی مدل‌ها و نماها هستند.

## استانداردهای کدنویسی

### استاندارد نامگذاری

- **کلاس‌ها**: با حرف بزرگ شروع می‌شوند و از استایل `PascalCase` استفاده می‌کنند. مثال: `UserController`
- **متدها**: با حرف کوچک شروع می‌شوند و از استایل `camelCase` استفاده می‌کنند. مثال: `getUserById`
- **متغیرها**: با حرف کوچک شروع می‌شوند و از استایل `camelCase` استفاده می‌کنند. مثال: `$userName`
- **ثابت‌ها**: همه حروف بزرگ و با جداکننده زیرخط. مثال: `DB_HOST`
- **فایل‌ها**: نام فایل‌های کلاس باید با نام کلاس یکسان باشد. مثال: `UserController.php`

### استاندارد کدنویسی PHP

- از PHP 8.1 استفاده می‌شود.
- از برچسب‌های استاندارد PHP استفاده کنید: `<?php ... ?>`
- در بالای هر فایل، یک بلاک توضیحات به فارسی قرار دهید:
  ```php
  /**
   * فایل: [مسیر فایل]
   * توضیحات: [توضیحات فایل]
   */
  ```
- از فضای خالی (indentation) 4 فاصله استفاده کنید.
- خطوط نباید بیش از 120 کاراکتر باشند.
- همیشه از آکولاد برای بلاک‌های کد استفاده کنید، حتی اگر فقط یک خط داشته باشند.
- از PSR-4 برای اتولودینگ کلاس‌ها استفاده می‌شود.

### استاندارد کدنویسی JavaScript

- از ES6 یا بالاتر استفاده کنید.
- از `const` و `let` به جای `var` استفاده کنید.
- از عملگرهای پیشرفته مانند destructuring و spread استفاده کنید.
- کدها را به صورت ماژولار بنویسید.
- از jQuery برای تعامل با DOM استفاده کنید.

### استاندارد کدنویسی CSS

- از استایل‌های Bootstrap 5 استفاده کنید و فقط در صورت نیاز، استایل‌های سفارشی اضافه کنید.
- از CSS3 و flexbox/grid برای طرح‌بندی استفاده کنید.
- از متغیرهای CSS برای رنگ‌ها و فونت‌ها استفاده کنید.
- از پیشوندهای مناسب برای سازگاری با مرورگرهای مختلف استفاده کنید.

## نحوه اضافه کردن ویژگی جدید

برای اضافه کردن یک ویژگی جدید به پروژه، مراحل زیر را دنبال کنید:

### 1. ایجاد جدول در پایگاه داده (در صورت نیاز)

اگر ویژگی جدید نیاز به ذخیره داده دارد، ابتدا اسکریپت SQL مربوطه را ایجاد کنید و در پوشه `database/migrations` قرار دهید.

مثال:
```sql
CREATE TABLE IF NOT EXISTS `new_feature` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. ایجاد مدل

مدل مربوط به ویژگی جدید را در پوشه `app/models` ایجاد کنید.

مثال (`app/models/NewFeature.php`):
```php
<?php
/**
 * فایل: /app/models/NewFeature.php
 * توضیحات: مدل برای ویژگی جدید
 */

class NewFeature
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    public function getAll()
    {
        return $this->db->query("SELECT * FROM new_feature ORDER BY created_at DESC")->fetchAll();
    }
    
    public function getById($id)
    {
        return $this->db->query("SELECT * FROM new_feature WHERE id = ?", [$id])->fetch();
    }
    
    public function create($data)
    {
        $this->db->query(
            "INSERT INTO new_feature (name, description) VALUES (?, ?)",
            [$data['name'], $data['description']]
        );
        
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data)
    {
        return $this->db->query(
            "UPDATE new_feature SET name = ?, description = ? WHERE id = ?",
            [$data['name'], $data['description'], $id]
        )->rowCount();
    }
    
    public function delete($id)
    {
        return $this->db->query("DELETE FROM new_feature WHERE id = ?", [$id])->rowCount();
    }
}
```

### 3. ایجاد کنترلر

کنترلر مربوط به ویژگی جدید را در پوشه `app/controllers` ایجاد کنید.

مثال (`app/controllers/NewFeatureController.php`):
```php
<?php
/**
 * فایل: /app/controllers/NewFeatureController.php
 * توضیحات: کنترلر برای ویژگی جدید
 */

class NewFeatureController
{
    private $model;
    
    public function __construct()
    {
        $this->model = new NewFeature();
    }
    
    public function index()
    {
        $features = $this->model->getAll();
        
        $pageTitle = 'لیست ویژگی‌های جدید';
        $viewPath = APP_PATH . '/views/new_feature/index.php';
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    public function show($id)
    {
        $feature = $this->model->getById($id);
        
        if (!$feature) {
            header('Location: ' . BASE_URL . '/404');
            exit;
        }
        
        $pageTitle = 'جزئیات ویژگی';
        $viewPath = APP_PATH . '/views/new_feature/show.php';
        
        require_once APP_PATH . '/views/layouts/main.php';
    }
    
    // ... سایر متدها مانند create, edit, update, delete
}
```

### 4. ایجاد نماها

نماهای مربوط به ویژگی جدید را در پوشه `app/views/new_feature` ایجاد کنید.

مثال (`app/views/new_feature/index.php`):
```php
<?php
/**
 * فایل: /app/views/new_feature/index.php
 * توضیحات: نمای لیست ویژگی‌های جدید
 */
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>لیست ویژگی‌های جدید</h1>
        <a href="<?php echo BASE_URL; ?>/new-feature/create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> افزودن ویژگی جدید
        </a>
    </div>
    
    <?php if (empty($features)): ?>
        <div class="alert alert-info">
            هیچ ویژگی جدیدی یافت نشد.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>تاریخ ایجاد</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($features as $index => $feature): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($feature['name']); ?></td>
                            <td><?php echo date('Y/m/d H:i', strtotime($feature['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/new-feature/<?php echo $feature['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>/new-feature/edit/<?php echo $feature['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>/new-feature/delete/<?php echo $feature['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('آیا مطمئن هستید؟')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
```

### 5. اضافه کردن مسیر (Route)

مسیرهای مربوط به ویژگی جدید را در فایل `app/Router.php` اضافه کنید:

```php
// مسیرهای ویژگی جدید
$router->get('/new-feature', 'NewFeatureController@index');
$router->get('/new-feature/create', 'NewFeatureController@create');
$router->post('/new-feature/store', 'NewFeatureController@store');
$router->get('/new-feature/{id}', 'NewFeatureController@show');
$router->get('/new-feature/edit/{id}', 'NewFeatureController@edit');
$router->post('/new-feature/update/{id}', 'NewFeatureController@update');
$router->get('/new-feature/delete/{id}', 'NewFeatureController@delete');
```

### 6. اضافه کردن لینک در منو

در صورت نیاز، لینک به ویژگی جدید را در منوی سایت اضافه کنید. فایل `app/views/components/header.php` را ویرایش کنید:

```php
<li class="nav-item">
    <a class="nav-link" href="<?php echo BASE_URL; ?>/new-feature">ویژگی جدید</a>
</li>
```

## نحوه اضافه کردن وابستگی‌های خارجی

برای اضافه کردن کتابخانه‌ها و وابستگی‌های خارجی، روش‌های زیر پیشنهاد می‌شود:

### روش 1: استفاده از CDN

برای کتابخانه‌های فرانت‌اند، استفاده از CDN روش ساده و کارآمدی است:

```html
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
```

### روش 2: دانلود دستی

برای کتابخانه‌های کوچک‌تر، می‌توانید فایل‌ها را دانلود کرده و در پوشه `public/assets` قرار دهید:

```
public/
├── assets/
│   ├── libs/
│   │   ├── chartjs/
│   │   ├── sweetalert/
│   │   └── ...
```

### روش 3: استفاده از Composer (برای PHP)

برای کتابخانه‌های PHP، از Composer استفاده کنید:

1. فایل `composer.json` را در ریشه پروژه ایجاد کنید:
   ```json
   {
       "require": {
           "phpmailer/phpmailer": "^6.5",
           "vlucas/phpdotenv": "^5.3"
       },
       "autoload": {
           "psr-4": {
               "App\\": "app/"
           }
       }
   }
   ```

2. Composer را نصب و اجرا کنید:
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs/cv123.ir
   composer install
   ```

3. از اتولودر Composer در فایل `index.php` استفاده کنید:
   ```php
   require_once __DIR__ . '/vendor/autoload.php';
   ```

## گزارش خطاها و دیباگ کردن

### لاگ کردن خطاها

برای ثبت خطاها، از کلاس `Logger` در `app/helpers/Logger.php` استفاده کنید:

```php
Logger::error('پیام خطا', ['context' => 'اطلاعات اضافی']);
Logger::info('پیام اطلاعاتی');
Logger::warning('پیام هشدار');
```

### نمایش خطاها در محیط توسعه

در محیط توسعه، خطاها نمایش داده می‌شوند. در محیط تولید، خطاها فقط در فایل لاگ ثبت می‌شوند:

```php
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}
```

## آزمون (Testing)

برای آزمون واحد و آزمون یکپارچه‌سازی، از PHPUnit استفاده کنید:

1. PHPUnit را نصب کنید:
   ```bash
   composer require --dev phpunit/phpunit
   ```

2. فایل پیکربندی `phpunit.xml` را در ریشه پروژه ایجاد کنید.

3. پوشه `tests` را برای آزمون‌ها ایجاد کنید:
   ```
   tests/
   ├── Unit/
   ├── Integration/
   └── bootstrap.php
   ```

4. آزمون‌ها را اجرا کنید:
   ```bash
   ./vendor/bin/phpunit
   ```

## به‌روزرسانی و انتشار

### مراحل به‌روزرسانی

1. کد‌ها را از مخزن به‌روزرسانی کنید.
2. اسکریپت‌های به‌روزرسانی پایگاه داده را اجرا کنید.
3. کش‌ها را پاک کنید.
4. سرویس‌ها را راه‌اندازی مجدد کنید.

### چک‌لیست انتشار

قبل از انتشار نسخه جدید، موارد زیر را بررسی کنید:

1. تمام تغییرات در پایگاه داده به‌درستی اعمال شده‌اند.
2. تست‌ها با موفقیت اجرا می‌شوند.
3. خطاهای امنیتی وجود ندارند.
4. کارایی برنامه قابل قبول است.
5. برنامه با همه مرورگرهای هدف سازگار است.
6. برنامه در تمام دستگاه‌های هدف به‌درستی کار می‌کند.

## منابع و مراجع

- [PHP 8.1 Documentation](https://www.php.net/docs.php)
- [MySQL 8.0 Reference Manual](https://dev.mysql.com/doc/refman/8.0/en/)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.0/getting-started/introduction/)
- [jQuery Documentation](https://api.jquery.com/)
- [MDN Web Docs](https://developer.mozilla.org/en-US/)
