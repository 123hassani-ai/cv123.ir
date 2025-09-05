<?php
/**
 * فایل: /app/views/admin/logs.php
 * توضیحات: صفحه مدیریت لاگ‌های سیستم در پنل ادمین
 */

// بررسی احراز هویت ادمین
if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت لاگ‌ها - پنل مدیریت CV123</title>
    
    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
            --dark-color: #212529;
            --light-color: #f8f9fa;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-sidebar {
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .main-content {
            margin-right: 280px;
            padding: 20px;
        }

        .dashboard-card {
            background: linear-gradient(135deg, var(--primary-color), #4dabf7);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .dashboard-card.success { background: linear-gradient(135deg, var(--success-color), #51cf66); }
        .dashboard-card.warning { background: linear-gradient(135deg, var(--warning-color), #ffd43b); color: #333; }
        .dashboard-card.danger { background: linear-gradient(135deg, var(--danger-color), #ff6b6b); }
        .dashboard-card.info { background: linear-gradient(135deg, var(--info-color), #74c0fc); color: #333; }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .card-icon {
            font-size: 3rem;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        .card-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .card-title {
            font-size: 1.1rem;
            margin-bottom: 0;
            opacity: 0.9;
        }

        .filters-panel {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
        }

        .logs-table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, var(--dark-color), #495057);
            color: white;
            padding: 20px 25px;
            margin: 0;
        }

        .logs-table {
            margin: 0;
        }

        .logs-table th {
            background: var(--light-color);
            border: none;
            padding: 15px 20px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .logs-table td {
            padding: 15px 20px;
            border: none;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
        }

        .logs-table tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        .level-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .level-SUCCESS { background: #d1f2eb; color: #155724; }
        .level-INFO { background: #cce7ff; color: #004085; }
        .level-WARNING { background: #fff3cd; color: #856404; }
        .level-ERROR { background: #f8d7da; color: #721c24; }
        .level-DEBUG { background: #e2e3e5; color: #383d41; }

        .category-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-left: 10px;
            font-size: 0.9rem;
        }

        .category-DATABASE { background: #e3f2fd; color: #1565c0; }
        .category-API { background: #f3e5f5; color: #7b1fa2; }
        .category-SYSTEM { background: #e8f5e8; color: #2e7d32; }
        .category-AUTH { background: #fff3e0; color: #f57c00; }
        .category-SMS { background: #fce4ec; color: #c2185b; }
        .category-AI { background: #e0f2f1; color: #00695c; }
        .category-USER { background: #f1f8e9; color: #558b2f; }

        .context-toggle {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 0.9rem;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }

        .context-toggle:hover {
            background: var(--light-color);
        }

        .context-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            border-right: 4px solid var(--primary-color);
        }

        .pagination-container {
            background: white;
            padding: 20px;
            border-radius: 0 0 15px 15px;
        }

        .btn-custom {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .toast-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 10000;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-right: 0;
                margin-top: 60px;
            }
            .admin-sidebar {
                position: fixed;
                top: 0;
                right: -280px;
                width: 280px;
                transition: right 0.3s ease;
                z-index: 1000;
            }
            .admin-sidebar.show {
                right: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto p-0">
                <?php include 'components/sidebar.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="col">
                <div class="main-content">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="mb-1">مدیریت لاگ‌های سیستم</h2>
                            <p class="text-muted mb-0">نظارت و مدیریت فعالیت‌های سیستم</p>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary btn-custom me-2" onclick="refreshData()">
                                <i class="bi bi-arrow-clockwise me-1"></i> بروزرسانی
                            </button>
                            <div class="btn-group">
                                <button class="btn btn-success btn-custom dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-tools me-1"></i> ابزارها
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="showCleanupModal()">
                                        <i class="bi bi-trash me-2"></i> پاکسازی لاگ‌ها
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="exportLogs()">
                                        <i class="bi bi-download me-2"></i> دانلود لاگ‌ها
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/settings">
                                        <i class="bi bi-gear me-2"></i> تنظیمات لاگ‌گیری
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Dashboard Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="dashboard-card">
                                <div class="card-icon">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <div class="card-number" id="totalLogsCount">
                                    <?php echo number_format($stats['total_logs'] ?? 0); ?>
                                </div>
                                <div class="card-title">کل لاگ‌ها</div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="dashboard-card danger">
                                <div class="card-icon">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="card-number" id="errorLogsCount">
                                    <?php echo number_format($stats['error_count'] ?? 0); ?>
                                </div>
                                <div class="card-title">خطاها</div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="dashboard-card success">
                                <div class="card-icon">
                                    <i class="bi bi-files"></i>
                                </div>
                                <div class="card-number" id="totalFilesCount">
                                    <?php echo number_format($stats['total_files'] ?? 0); ?>
                                </div>
                                <div class="card-title">فایل‌ها</div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="dashboard-card info">
                                <div class="card-icon">
                                    <i class="bi bi-hdd"></i>
                                </div>
                                <div class="card-number" id="totalSizeCount">
                                    <?php echo $stats['total_size_mb'] ?? '0.00'; ?> MB
                                </div>
                                <div class="card-title">حجم فایل‌ها</div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters Panel -->
                    <div class="filters-panel">
                        <h5 class="mb-3"><i class="bi bi-funnel me-2"></i>فیلترهای جستجو</h5>
                        <form id="filtersForm" onsubmit="return false;">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">از تاریخ</label>
                                    <input type="date" class="form-control" name="date_from" id="dateFrom" 
                                           value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">تا تاریخ</label>
                                    <input type="date" class="form-control" name="date_to" id="dateTo"
                                           value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">سطح لاگ</label>
                                    <select class="form-select" name="level" id="levelFilter">
                                        <option value="">همه سطوح</option>
                                        <option value="SUCCESS" <?php echo $filters['level'] === 'SUCCESS' ? 'selected' : ''; ?>>SUCCESS</option>
                                        <option value="INFO" <?php echo $filters['level'] === 'INFO' ? 'selected' : ''; ?>>INFO</option>
                                        <option value="WARNING" <?php echo $filters['level'] === 'WARNING' ? 'selected' : ''; ?>>WARNING</option>
                                        <option value="ERROR" <?php echo $filters['level'] === 'ERROR' ? 'selected' : ''; ?>>ERROR</option>
                                        <option value="DEBUG" <?php echo $filters['level'] === 'DEBUG' ? 'selected' : ''; ?>>DEBUG</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">دسته</label>
                                    <select class="form-select" name="category" id="categoryFilter">
                                        <option value="">همه دسته‌ها</option>
                                        <option value="DATABASE" <?php echo $filters['category'] === 'DATABASE' ? 'selected' : ''; ?>>DATABASE</option>
                                        <option value="API" <?php echo $filters['category'] === 'API' ? 'selected' : ''; ?>>API</option>
                                        <option value="SYSTEM" <?php echo $filters['category'] === 'SYSTEM' ? 'selected' : ''; ?>>SYSTEM</option>
                                        <option value="AUTH" <?php echo $filters['category'] === 'AUTH' ? 'selected' : ''; ?>>AUTH</option>
                                        <option value="SMS" <?php echo $filters['category'] === 'SMS' ? 'selected' : ''; ?>>SMS</option>
                                        <option value="AI" <?php echo $filters['category'] === 'AI' ? 'selected' : ''; ?>>AI</option>
                                        <option value="USER" <?php echo $filters['category'] === 'USER' ? 'selected' : ''; ?>>USER</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">جستجوی متن</label>
                                    <input type="text" class="form-control" name="search" id="searchFilter" 
                                           placeholder="جستجو در پیام‌ها..." value="<?php echo htmlspecialchars($filters['search']); ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">تعداد نمایش</label>
                                    <select class="form-select" name="limit" id="limitFilter">
                                        <option value="25" <?php echo $filters['limit'] === 25 ? 'selected' : ''; ?>>25</option>
                                        <option value="50" <?php echo $filters['limit'] === 50 ? 'selected' : ''; ?>>50</option>
                                        <option value="100" <?php echo $filters['limit'] === 100 ? 'selected' : ''; ?>>100</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <div class="d-grid gap-2 d-md-flex w-100">
                                        <button type="button" class="btn btn-primary btn-custom" onclick="applyFilters()">
                                            <i class="bi bi-search me-1"></i> جستجو
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-custom" onclick="clearFilters()">
                                            <i class="bi bi-x-circle me-1"></i> پاک کردن
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Logs Table -->
                    <div class="logs-table-container">
                        <div class="table-header">
                            <h5 class="mb-0">
                                <i class="bi bi-list-ul me-2"></i>
                                لیست لاگ‌ها
                                <span class="badge bg-light text-dark me-2" id="logsCount">
                                    <?php echo count($logs['data']); ?> رکورد
                                </span>
                            </h5>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table logs-table" id="logsTable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">زمان</th>
                                        <th width="8%">سطح</th>
                                        <th width="10%">دسته</th>
                                        <th width="12%">کاربر</th>
                                        <th width="35%">پیام</th>
                                        <th width="15%">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="logsTableBody">
                                    <!-- محتوا توسط JavaScript پر می‌شود -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-container" id="paginationContainer">
                            <!-- Pagination توسط JavaScript پر می‌شود -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cleanup Modal -->
    <div class="modal fade" id="cleanupModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">پاکسازی لاگ‌های قدیمی</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">حذف لاگ‌های قدیمی‌تر از:</label>
                        <select class="form-select" id="cleanupDays">
                            <option value="7">7 روز</option>
                            <option value="14">14 روز</option>
                            <option value="30" selected>30 روز</option>
                            <option value="60">60 روز</option>
                            <option value="90">90 روز</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        توجه: این عملیات قابل بازگشت نیست!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-danger" onclick="cleanupLogs()">پاکسازی</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // متغیرهای سراسری
        let currentPage = 1;
        let currentFilters = {};
        
        // بارگذاری اولیه صفحه
        document.addEventListener('DOMContentLoaded', function() {
            loadLogs();
        });
        
        // بارگذاری لاگ‌ها
        function loadLogs(page = 1) {
            showLoading(true);
            currentPage = page;
            
            // جمع‌آوری فیلترها
            currentFilters = {
                date_from: document.getElementById('dateFrom').value,
                date_to: document.getElementById('dateTo').value,
                level: document.getElementById('levelFilter').value,
                category: document.getElementById('categoryFilter').value,
                search: document.getElementById('searchFilter').value,
                limit: parseInt(document.getElementById('limitFilter').value),
                page: page
            };
            
            // ارسال درخواست AJAX
            const formData = new FormData();
            for (const [key, value] of Object.entries(currentFilters)) {
                formData.append(key, value);
            }
            
            fetch('<?php echo BASE_URL; ?>/admin/logs/ajax', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                
                if (data.success) {
                    renderLogsTable(data.data);
                    renderPagination(data);
                    updateLogsCount(data.total_logs);
                } else {
                    showToast('خطا در بارگذاری لاگ‌ها: ' + data.error, 'error');
                }
            })
            .catch(error => {
                showLoading(false);
                console.error('Error:', error);
                showToast('خطا در ارتباط با سرور', 'error');
            });
        }
        
        // رندر جدول لاگ‌ها
        function renderLogsTable(logs) {
            const tbody = document.getElementById('logsTableBody');
            tbody.innerHTML = '';
            
            if (logs.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">هیچ لاگی یافت نشد</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            logs.forEach((log, index) => {
                const row = document.createElement('tr');
                const globalIndex = (currentPage - 1) * currentFilters.limit + index + 1;
                
                row.innerHTML = `
                    <td>${globalIndex}</td>
                    <td>
                        <div class="text-nowrap">
                            ${formatDateTime(log.timestamp)}
                        </div>
                    </td>
                    <td>
                        <span class="level-badge level-${log.level || 'INFO'}">
                            ${log.level || 'INFO'}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="category-icon category-${log.category || 'SYSTEM'}">
                                ${getCategoryIcon(log.category)}
                            </span>
                            <span>${log.category || 'SYSTEM'}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-truncate" title="${log.username || 'guest'}">
                            <i class="bi bi-person-circle me-1"></i>
                            ${log.username || 'guest'}
                        </div>
                    </td>
                    <td>
                        <div class="text-truncate" title="${log.message_fa || ''}">
                            ${log.message_fa || log.message_en || ''}
                        </div>
                        ${log.message_en && log.message_fa !== log.message_en ? 
                            `<small class="text-muted">${log.message_en}</small>` : ''}
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary btn-sm context-toggle" 
                                    onclick="toggleContext('${log.id}')">
                                <i class="bi bi-eye"></i>
                            </button>
                            ${log.context ? `
                                <button class="btn btn-outline-info btn-sm" 
                                        onclick="showLogDetails('${log.id}')">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            ` : ''}
                        </div>
                        <div id="context-${log.id}" class="context-details" style="display: none;">
                            ${renderContext(log.context)}
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }
        
        // رندر pagination
        function renderPagination(data) {
            const container = document.getElementById('paginationContainer');
            
            if (data.total_pages <= 1) {
                container.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">نمایش ${data.data.length} رکورد از ${data.total_logs}</span>
                    </div>
                `;
                return;
            }
            
            let paginationHtml = `
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">
                        صفحه ${data.current_page} از ${data.total_pages} 
                        (${data.total_logs} رکورد)
                    </span>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
            `;
            
            // دکمه قبلی
            if (data.current_page > 1) {
                paginationHtml += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadLogs(${data.current_page - 1})">قبلی</a>
                    </li>
                `;
            }
            
            // شماره صفحات
            for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.total_pages, data.current_page + 2); i++) {
                paginationHtml += `
                    <li class="page-item ${i === data.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadLogs(${i})">${i}</a>
                    </li>
                `;
            }
            
            // دکمه بعدی
            if (data.current_page < data.total_pages) {
                paginationHtml += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadLogs(${data.current_page + 1})">بعدی</a>
                    </li>
                `;
            }
            
            paginationHtml += `
                        </ul>
                    </nav>
                </div>
            `;
            
            container.innerHTML = paginationHtml;
        }
        
        // Helper functions
        function formatDateTime(dateTime) {
            if (!dateTime) return '-';
            const date = new Date(dateTime);
            return `
                <div>${date.toLocaleDateString('fa-IR')}</div>
                <small class="text-muted">${date.toLocaleTimeString('fa-IR')}</small>
            `;
        }
        
        function getCategoryIcon(category) {
            const icons = {
                'DATABASE': '<i class="bi bi-database"></i>',
                'API': '<i class="bi bi-cloud"></i>',
                'SYSTEM': '<i class="bi bi-cpu"></i>',
                'AUTH': '<i class="bi bi-shield-check"></i>',
                'SMS': '<i class="bi bi-phone"></i>',
                'AI': '<i class="bi bi-robot"></i>',
                'USER': '<i class="bi bi-person"></i>'
            };
            return icons[category] || '<i class="bi bi-gear"></i>';
        }
        
        function renderContext(context) {
            if (!context || typeof context !== 'object') return '';
            
            let html = '<div class="row">';
            for (const [key, value] of Object.entries(context)) {
                html += `
                    <div class="col-md-6 mb-2">
                        <strong>${key}:</strong> 
                        <span class="text-muted">${JSON.stringify(value)}</span>
                    </div>
                `;
            }
            html += '</div>';
            return html;
        }
        
        // Event handlers
        function applyFilters() {
            loadLogs(1);
        }
        
        function clearFilters() {
            document.getElementById('filtersForm').reset();
            loadLogs(1);
        }
        
        function refreshData() {
            location.reload();
        }
        
        function toggleContext(logId) {
            const element = document.getElementById(`context-${logId}`);
            element.style.display = element.style.display === 'none' ? 'block' : 'none';
        }
        
        function showCleanupModal() {
            new bootstrap.Modal(document.getElementById('cleanupModal')).show();
        }
        
        function cleanupLogs() {
            const days = document.getElementById('cleanupDays').value;
            
            showLoading(true);
            
            const formData = new FormData();
            formData.append('days', days);
            
            fetch('<?php echo BASE_URL; ?>/admin/logs/cleanup', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                
                if (data.success) {
                    showToast(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('cleanupModal')).hide();
                    setTimeout(refreshData, 2000);
                } else {
                    showToast('خطا: ' + data.error, 'error');
                }
            })
            .catch(error => {
                showLoading(false);
                console.error('Error:', error);
                showToast('خطا در ارتباط با سرور', 'error');
            });
        }
        
        function exportLogs() {
            showToast('قابلیت دانلود بزودی اضافه خواهد شد', 'info');
        }
        
        function updateLogsCount(count) {
            document.getElementById('logsCount').textContent = `${count} رکورد`;
        }
        
        function showLoading(show) {
            document.getElementById('loadingOverlay').style.display = show ? 'flex' : 'none';
        }
        
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            const bgClass = {
                'success': 'bg-success',
                'error': 'bg-danger',
                'warning': 'bg-warning',
                'info': 'bg-primary'
            }[type] || 'bg-primary';
            
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toast = new bootstrap.Toast(document.getElementById(toastId));
            toast.show();
            
            // حذف خودکار پس از 5 ثانیه
            setTimeout(() => {
                const toastElement = document.getElementById(toastId);
                if (toastElement) {
                    toastElement.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>
