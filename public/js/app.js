/**
 * فایل: /public/js/app.js
 * توضیحات: اسکریپت‌های اصلی سایت
 */

// اجرای کد بعد از بارگذاری کامل صفحه
document.addEventListener('DOMContentLoaded', function() {
    // اضافه کردن کلاس به المان‌ها برای اعمال انیمیشن
    const animElements = document.querySelectorAll('.card, .btn-primary, h1, .lead');
    animElements.forEach(el => {
        el.classList.add('fade-in');
    });
    
    // نمایش اعلان‌ها با تأخیر و حذف خودکار
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            setTimeout(() => {
                bsAlert.close();
            }, 5000);
        }, 500);
    });
    
    // اعتبارسنجی فرم‌ها
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    // نمایش تولتیپ‌ها
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    
    // نمایش پاپ‌اورها
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
});

// درخواست‌های AJAX با jQuery
$(document).ready(function() {
    // ارسال فرم‌ها با AJAX
    $('.ajax-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        const method = form.attr('method') || 'POST';
        const formData = new FormData(this);
        
        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...');
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    
                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1500);
                    }
                } else {
                    showNotification('danger', response.message || 'خطایی رخ داده است. لطفا دوباره تلاش کنید.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'خطایی در ارتباط با سرور رخ داده است.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showNotification('danger', errorMessage);
            },
            complete: function() {
                form.find('button[type="submit"]').prop('disabled', false).html('ارسال');
            }
        });
    });
});

/**
 * نمایش اعلان
 * @param {string} type نوع اعلان (success, danger, warning, info)
 * @param {string} message متن پیام
 */
function showNotification(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 shadow-sm`;
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => {
            alertDiv.remove();
        }, 300);
    }, 5000);
}
