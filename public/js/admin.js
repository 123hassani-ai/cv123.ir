/**
 * فایل: /public/js/admin.js
 * توضیحات: اسکریپت‌های اختصاصی پنل مدیریت
 */

document.addEventListener('DOMContentLoaded', function() {
    // نمایش پیام‌های سیستم
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // حذف خودکار پیام‌ها پس از 5 ثانیه
        setTimeout(() => {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // تغییر ارتفاع سایدبار با تغییر اندازه صفحه
    function adjustSidebarHeight() {
        const sidebar = document.querySelector('.admin-sidebar');
        if (sidebar) {
            sidebar.style.minHeight = window.innerHeight + 'px';
        }
    }
    
    adjustSidebarHeight();
    window.addEventListener('resize', adjustSidebarHeight);
    
    // افزودن توضیحات به المان‌های دارای data-bs-toggle="tooltip"
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // اضافه کردن حالت انتخاب به جدول‌ها
    const tableRows = document.querySelectorAll('table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function() {
            if (!this.classList.contains('no-select')) {
                this.classList.toggle('table-active');
            }
        });
    });
    
    // حذف کلاس table-active از سایر ردیف‌های جدول هنگامی که از کلیک روی آن‌ها خارج می‌شویم
    document.addEventListener('click', function(e) {
        if (!e.target.closest('table') && !e.target.closest('.no-reset-selection')) {
            document.querySelectorAll('table tbody tr.table-active').forEach(row => {
                row.classList.remove('table-active');
            });
        }
    });

    // ارسال فرم‌ها با AJAX
    const ajaxForms = document.querySelectorAll('form.ajax-form');
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const url = form.getAttribute('action');
            const method = form.getAttribute('method') || 'POST';
            const submitBtn = form.querySelector('[type="submit"]');
            
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...';
                
                fetch(url, {
                    method: method,
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showMessage(data.message || 'عملیات با موفقیت انجام شد', 'success');
                        
                        // اجرای عملیات پس از موفقیت
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000);
                        }
                        
                        if (data.reload) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                        
                        // پاک کردن فرم در صورت نیاز
                        if (data.clearForm) {
                            form.reset();
                        }
                    } else {
                        showMessage(data.message || 'خطا در انجام عملیات', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('خطا در ارتباط با سرور', 'danger');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            }
        });
    });
});

// تابع نمایش پیام
function showMessage(message, type = 'info') {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type} alert-dismissible fade show`;
    alertContainer.role = 'alert';
    alertContainer.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="بستن"></button>
    `;
    
    // افزودن به DOM
    const contentWrapper = document.querySelector('.content-wrapper');
    if (contentWrapper) {
        contentWrapper.insertBefore(alertContainer, contentWrapper.firstChild);
    }
    
    // حذف خودکار پس از 5 ثانیه
    setTimeout(() => {
        if (alertContainer) {
            const bsAlert = new bootstrap.Alert(alertContainer);
            bsAlert.close();
        }
    }, 5000);
}
