<!-- محتوای صفحه مدیریت لاگ‌ها -->

<!-- آمار -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">کل لاگ‌ها</h6>
                        <h2 class="mb-0"><?php echo number_format($stats['total_logs'] ?? 0); ?></h2>
                    </div>
                    <i class="bi bi-file-text" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">خطاها</h6>
                        <h2 class="mb-0"><?php echo number_format($stats['error_count'] ?? 0); ?></h2>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">فایل‌ها</h6>
                        <h2 class="mb-0"><?php echo number_format($stats['total_files'] ?? 0); ?></h2>
                    </div>
                    <i class="bi bi-folder" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">حجم فایل‌ها</h6>
                        <h2 class="mb-0"><?php echo $stats['total_size_mb'] ?? 0; ?> مگ</h2>
                    </div>
                    <i class="bi bi-hdd" style="font-size: 2.5rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- فیلترها -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>فیلترهای جستجو</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>/admin/logs">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">جستجو در پیام‌ها</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="جستجو..." 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">سطح لاگ</label>
                    <select name="level" class="form-select">
                        <option value="">همه سطوح</option>
                        <option value="INFO" <?php echo ($_GET['level'] ?? '') === 'INFO' ? 'selected' : ''; ?>>INFO</option>
                        <option value="ERROR" <?php echo ($_GET['level'] ?? '') === 'ERROR' ? 'selected' : ''; ?>>ERROR</option>
                        <option value="DEBUG" <?php echo ($_GET['level'] ?? '') === 'DEBUG' ? 'selected' : ''; ?>>DEBUG</option>
                        <option value="WARNING" <?php echo ($_GET['level'] ?? '') === 'WARNING' ? 'selected' : ''; ?>>WARNING</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">دسته‌بندی</label>
                    <select name="category" class="form-select">
                        <option value="">همه دسته‌ها</option>
                        <option value="auth" <?php echo ($_GET['category'] ?? '') === 'auth' ? 'selected' : ''; ?>>احراز هویت</option>
                        <option value="database" <?php echo ($_GET['category'] ?? '') === 'database' ? 'selected' : ''; ?>>دیتابیس</option>
                        <option value="api" <?php echo ($_GET['category'] ?? '') === 'api' ? 'selected' : ''; ?>>API</option>
                        <option value="user" <?php echo ($_GET['category'] ?? '') === 'user' ? 'selected' : ''; ?>>کاربر</option>
                        <option value="system" <?php echo ($_GET['category'] ?? '') === 'system' ? 'selected' : ''; ?>>سیستم</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>جستجو
                        </button>
                        <a href="<?php echo BASE_URL; ?>/admin/logs" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-clockwise me-1"></i>پاک کردن
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- جدول لاگ‌ها -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>لیست لاگ‌ها</h5>
            <div class="d-flex gap-2">
                <small class="text-muted">آخرین 50 لاگ</small>
                <button class="btn btn-outline-primary btn-sm" onclick="refreshLogs()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="12%">زمان</th>
                        <th width="8%">سطح</th>
                        <th width="10%">دسته</th>
                        <th width="40%">پیام</th>
                        <th width="10%">کاربر</th>
                        <th width="20%">فایل</th>
                    </tr>
                </thead>
                <tbody id="logs-table-body">
                    <?php if (isset($logs['data']) && !empty($logs['data'])): ?>
                        <?php foreach ($logs['data'] as $index => $log): ?>
                            <tr class="<?php echo $index % 2 === 0 ? '' : 'table-light'; ?>">
                                <td>
                                    <div class="text-nowrap">
                                        <?php 
                                        $time = $log['time'] ?? '';
                                        if ($time) {
                                            echo '<strong>' . date('H:i:s', strtotime($time)) . '</strong>';
                                            echo '<br><small class="text-muted">' . date('Y/m/d', strtotime($time)) . '</small>';
                                        }
                                        ?>
                                    </div>
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
                                    <span class="badge bg-light text-dark border">
                                        <?php echo htmlspecialchars($log['cat'] ?? '-'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-break">
                                        <strong><?php echo htmlspecialchars($log['msg'] ?? ''); ?></strong>
                                        <?php if (!empty($log['msg_en']) && $log['msg_en'] !== ($log['msg'] ?? '')): ?>
                                            <br><small class="text-muted">
                                                <?php echo htmlspecialchars($log['msg_en']); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-white">
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
                                <div class="text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
                                    <div class="mt-2">هیچ لاگی یافت نشد</div>
                                    <small>ممکن است فیلترهای شما نتیجه‌ای نداشته باشد</small>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if (isset($logs['total']) && $logs['total'] > 50): ?>
        <div class="card-footer bg-light">
            <small class="text-muted">
                نمایش 50 لاگ از مجموع <?php echo number_format($logs['total']); ?> لاگ
            </small>
        </div>
    <?php endif; ?>
</div>

<script>
function refreshLogs() {
    location.reload();
}

// بروزرسانی خودکار هر 30 ثانیه
setInterval(function() {
    // می‌توانید AJAX برای بروزرسانی خودکار اضافه کنید
}, 30000);
</script>
