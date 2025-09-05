<?php
/**
 * فایل: /app/views/admin/dashboard.php
 * توضیحات: نمای داشبورد مدیریت
 */
?>

<div class="row mb-4">
    <div class="col">
        <h2 class="h4 mb-3">
            <i class="bi bi-speedometer2 me-2"></i>
            داشبورد مدیریت
        </h2>
        <p class="text-muted">خلاصه آمار و وضعیت سیستم</p>
    </div>
</div>

<!-- کارت‌های آمار -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">کاربران</div>
                        <h3 class="stat-value mb-2"><?php echo number_format($stats['users']); ?></h3>
                        <div class="text-success small">
                            <i class="bi bi-arrow-up-right"></i>
                            <span>14% رشد در ماه اخیر</span>
                        </div>
                    </div>
                    <div class="stat-icon bg-light-primary text-primary rounded">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">فرصت‌های شغلی</div>
                        <h3 class="stat-value mb-2"><?php echo number_format($stats['jobs']); ?></h3>
                        <div class="text-success small">
                            <i class="bi bi-arrow-up-right"></i>
                            <span>8% رشد در ماه اخیر</span>
                        </div>
                    </div>
                    <div class="stat-icon bg-light-info text-info rounded">
                        <i class="bi bi-briefcase"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">درخواست‌ها</div>
                        <h3 class="stat-value mb-2"><?php echo number_format($stats['applications']); ?></h3>
                        <div class="text-success small">
                            <i class="bi bi-arrow-up-right"></i>
                            <span>21% رشد در ماه اخیر</span>
                        </div>
                    </div>
                    <div class="stat-icon bg-light-warning text-warning rounded">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card stat-card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small mb-1">مصاحبه‌ها</div>
                        <h3 class="stat-value mb-2"><?php echo number_format($stats['interviews']); ?></h3>
                        <div class="text-danger small">
                            <i class="bi bi-arrow-down-right"></i>
                            <span>3% کاهش در ماه اخیر</span>
                        </div>
                    </div>
                    <div class="stat-icon bg-light-danger text-danger rounded">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- وضعیت سیستم و اقدامات سریع -->
<div class="row mb-4">
    <!-- وضعیت سیستم -->
    <div class="col-lg-8 mb-3">
        <div class="card system-card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">وضعیت سیستم</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            7 روز اخیر
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">امروز</a></li>
                            <li><a class="dropdown-item" href="#">هفته اخیر</a></li>
                            <li><a class="dropdown-item" href="#">ماه اخیر</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-muted">وضعیت پردازش درخواست‌ها</div>
                        <div class="text-muted">78%</div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-muted">وضعیت مصاحبه‌های هوش مصنوعی</div>
                        <div class="text-muted">62%</div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-muted">اشغال فضای دیسک</div>
                        <div class="text-muted">45%</div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div>
                    <div class="d-flex justify-content-between mb-2">
                        <div class="text-muted">مصرف پهنای باند</div>
                        <div class="text-muted">23%</div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 23%" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- اقدامات سریع -->
    <div class="col-lg-4 mb-3">
        <div class="card system-card border-0 shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">اقدامات سریع</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>/admin/jobs/add" class="list-group-item list-group-item-action d-flex align-items-center border-0 px-0">
                        <div class="me-3 p-2 bg-light-primary text-primary rounded">
                            <i class="bi bi-plus-square"></i>
                        </div>
                        <div>
                            <div class="fw-bold">افزودن فرصت شغلی جدید</div>
                            <small class="text-muted">ثبت موقعیت جدید استخدامی</small>
                        </div>
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>/admin/applications" class="list-group-item list-group-item-action d-flex align-items-center border-0 px-0">
                        <div class="me-3 p-2 bg-light-info text-info rounded">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>
                        <div>
                            <div class="fw-bold">بررسی درخواست‌های جدید</div>
                            <small class="text-muted">12 درخواست جدید در انتظار بررسی</small>
                        </div>
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>/admin/interviews" class="list-group-item list-group-item-action d-flex align-items-center border-0 px-0">
                        <div class="me-3 p-2 bg-light-warning text-warning rounded">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <div class="fw-bold">زمان‌بندی مصاحبه‌ها</div>
                            <small class="text-muted">مصاحبه‌های امروز و فردا</small>
                        </div>
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>/admin/settings" class="list-group-item list-group-item-action d-flex align-items-center border-0 px-0">
                        <div class="me-3 p-2 bg-light-success text-success rounded">
                            <i class="bi bi-gear"></i>
                        </div>
                        <div>
                            <div class="fw-bold">تنظیمات سیستم</div>
                            <small class="text-muted">پیکربندی API و سرویس‌های خارجی</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- آخرین فعالیت‌ها -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card system-card border-0 shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">آخرین فعالیت‌ها</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">مشاهده همه</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 50px">#</th>
                                <th scope="col">رویداد</th>
                                <th scope="col">کاربر</th>
                                <th scope="col">تاریخ</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>ثبت درخواست استخدام</td>
                                <td>علی محمدی</td>
                                <td>1402/06/14 - 10:23</td>
                                <td><span class="badge bg-info">جدید</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" class="btn btn-outline-primary">مشاهده</a>
                                        <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">بیشتر</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">تایید</a></li>
                                            <li><a class="dropdown-item" href="#">رد</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#">حذف</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>برگزاری مصاحبه</td>
                                <td>سارا احمدی</td>
                                <td>1402/06/14 - 09:15</td>
                                <td><span class="badge bg-success">کامل شده</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" class="btn btn-outline-primary">مشاهده</a>
                                        <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">بیشتر</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">دانلود گزارش</a></li>
                                            <li><a class="dropdown-item" href="#">ارسال ایمیل</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#">حذف</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>ایجاد فرصت شغلی جدید</td>
                                <td>مدیر سیستم</td>
                                <td>1402/06/13 - 15:40</td>
                                <td><span class="badge bg-warning text-dark">در انتظار تایید</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" class="btn btn-outline-primary">مشاهده</a>
                                        <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">بیشتر</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">ویرایش</a></li>
                                            <li><a class="dropdown-item" href="#">انتشار</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#">حذف</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
                        <small class="text-muted">۳۵ دقیقه پیش</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span>
                            <i class="bi bi-briefcase text-warning me-2"></i>
                            بروزرسانی موقعیت شغلی
                        </span>
                        <small class="text-muted">۲ ساعت پیش</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span>
                            <i class="bi bi-envelope text-info me-2"></i>
                            ارسال پیامک گروهی
                        </span>
                        <small class="text-muted">دیروز</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <span>
                            <i class="bi bi-check-circle text-success me-2"></i>
                            تأیید مصاحبه
                        </span>
                        <small class="text-muted">دیروز</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100 bg-white">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>وضعیت سیستم</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span>اتصال به سرویس پیامک</span>
                        <span class="badge bg-success">متصل</span>
                    </div>
<style>
/* رنگ‌های پس‌زمینه برای آیکون‌ها */
.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-light-success {
    background-color: rgba(25, 135, 84, 0.1);
}
.bg-light-warning {
    background-color: rgba(255, 193, 7, 0.1);
}
.bg-light-danger {
    background-color: rgba(220, 53, 69, 0.1);
}
.bg-light-info {
    background-color: rgba(13, 202, 240, 0.1);
}

/* استایل آیکون آمار */
.stat-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
</style>
