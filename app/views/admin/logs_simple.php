<?php
// بررسی احراز هویت ادمین
if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/login');
    exit;
}

// بارگذاری کانفیگ در صورت عدم وجود
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/config.php';
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت لاگ‌ها - CV123</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/fonts/vazirmatn/vazirmatn.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Vazirmatn', 'Tahoma', sans-serif !important;
        }
        body {
            background-color: #f8f9fa;
            font-size: 14px;
        }
        .sidebar {
            min-height: 100vh;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .card {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 1.5rem;
            border-radius: 10px;
        }
        .card-title {
            font-weight: bold;
            color: #495057;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            border-top: none;
            color: #495057;
        }
        .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.75em;
        }
        .nav-link.active {
            background-color: #007bff !important;
            border-radius: 5px;
        }
        .btn {
            border-radius: 8px;
            font-weight: bold;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
        }
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
            <div class="position-sticky pt-3">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/admin">
                                <i class="bi bi-house"></i> داشبورد
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/admin/applicants">
                                <i class="bi bi-people"></i> متقاضیان
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active bg-primary" href="<?php echo BASE_URL; ?>/admin/logs">
                                <i class="bi bi-file-text"></i> مدیریت لاگ‌ها
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/admin/settings">
                                <i class="bi bi-gear"></i> تنظیمات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/admin/logout">
                                <i class="bi bi-box-arrow-right"></i> خروج
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">مدیریت لاگ‌ها</h1>
            </div>

            <!-- آمار -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card bg-primary text-white">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-1">کل لاگ‌ها</h6>
                                    <h2 class="mb-0"><?php echo number_format($stats['total_logs'] ?? 0); ?></h2>
                                </div>
                                <i class="bi bi-file-text fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card bg-danger text-white">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-1">خطاها</h6>
                                    <h2 class="mb-0"><?php echo number_format($stats['error_count'] ?? 0); ?></h2>
                                </div>
                                <i class="bi bi-exclamation-triangle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card bg-success text-white">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-1">فایل‌ها</h6>
                                    <h2 class="mb-0"><?php echo number_format($stats['total_files'] ?? 0); ?></h2>
                                </div>
                                <i class="bi bi-folder fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card bg-info text-white">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-1">حجم فایل‌ها</h6>
                                    <h2 class="mb-0"><?php echo $stats['total_size_mb'] ?? 0; ?> مگ</h2>
                                </div>
                                <i class="bi bi-hdd fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- فیلترها -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="جستجو..." 
                                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="level" class="form-select">
                                    <option value="">همه سطوح</option>
                                    <option value="INFO" <?php echo ($_GET['level'] ?? '') === 'INFO' ? 'selected' : ''; ?>>INFO</option>
                                    <option value="ERROR" <?php echo ($_GET['level'] ?? '') === 'ERROR' ? 'selected' : ''; ?>>ERROR</option>
                                    <option value="DEBUG" <?php echo ($_GET['level'] ?? '') === 'DEBUG' ? 'selected' : ''; ?>>DEBUG</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">جستجو</button>
                                <a href="?" class="btn btn-secondary">پاک کردن</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- جدول لاگ‌ها -->
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>لیست لاگ‌ها</h5>
                        <small class="text-muted">آخرین 50 لاگ</small>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="15%">زمان</th>
                                    <th width="10%">سطح</th>
                                    <th width="10%">دسته</th>
                                    <th width="35%">پیام</th>
                                    <th width="10%">کاربر</th>
                                    <th width="20%">فایل</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($logs['data']) && !empty($logs['data'])): ?>
                                    <?php foreach ($logs['data'] as $index => $log): ?>
                                        <tr class="<?php echo $index % 2 === 0 ? 'bg-light' : ''; ?>">
                                            <td>
                                                <small class="text-muted">
                                                    <?php 
                                                    $time = $log['time'] ?? '';
                                                    if ($time) {
                                                        echo date('H:i:s', strtotime($time));
                                                        echo '<br><span class="text-muted">' . date('Y/m/d', strtotime($time)) . '</span>';
                                                    }
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php
                                                $level = $log['lv'] ?? '';
                                                $badgeClass = match($level) {
                                                    'ERROR' => 'bg-danger',
                                                    'INFO' => 'bg-primary', 
                                                    'DEBUG' => 'bg-secondary',
                                                    'SUCCESS' => 'bg-success',
                                                    'WARNING' => 'bg-warning',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>">
                                                    <?php echo htmlspecialchars($level); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <?php echo htmlspecialchars($log['cat'] ?? '-'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;" title="<?php echo htmlspecialchars($log['msg'] ?? ''); ?>">
                                                    <?php echo htmlspecialchars($log['msg'] ?? ''); ?>
                                                </div>
                                                <?php if (!empty($log['msg_en']) && $log['msg_en'] !== ($log['msg'] ?? '')): ?>
                                                    <small class="text-muted d-block">
                                                        <?php echo htmlspecialchars($log['msg_en']); ?>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo htmlspecialchars($log['user'] ?? 'مهمان'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php 
                                                    $file = $log['file'] ?? '';
                                                    if ($file) {
                                                        $file = basename($file);
                                                        echo htmlspecialchars($file);
                                                        if (!empty($log['line'])) {
                                                            echo ':' . $log['line'];
                                                        }
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                            <span class="text-muted">هیچ لاگی یافت نشد</span>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
