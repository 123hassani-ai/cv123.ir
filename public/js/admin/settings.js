/**
 * فایل: /public/js/admin/settings.js
 * توضیحات: اسکریپت صفحه تنظیمات مدیریت
 */

document.addEventListener('DOMContentLoaded', function() {
    // اعتبارسنجی فرم
    const form = document.querySelector('.needs-validation');
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    }
    
    // نمایش تب فعال براساس هش URL
    const hash = window.location.hash;
    if (hash) {
        const tabId = hash.replace('#', '') + '-tab';
        const tab = document.getElementById(tabId);
        if (tab) {
            const bsTab = new bootstrap.Tab(tab);
            bsTab.show();
        }
    }
    
    // ذخیره تب فعال در URL
    const tabLinks = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabLinks.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(event) {
            const id = event.target.id.replace('-tab', '');
            window.location.hash = id;
        });
    });
    
    // تست اتصال به OpenAI
    const testOpenAIBtn = document.getElementById('test_openai');
    if (testOpenAIBtn) {
        testOpenAIBtn.addEventListener('click', async function() {
            const apiKey = document.getElementById('openai_api_key').value;
            if (!apiKey) {
                showMessage('لطفاً کلید API را وارد کنید.', 'warning');
                return;
            }
            
            // نمایش حالت در حال بارگذاری
            const originalText = testOpenAIBtn.innerHTML;
            testOpenAIBtn.disabled = true;
            testOpenAIBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال تست...';
            
            try {
                const response = await fetch(baseUrl + '/admin/settings/test-openai', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'api_key=' + encodeURIComponent(apiKey)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showMessage(result.message, 'success');
                } else {
                    showMessage(result.message, 'danger');
                }
            } catch (error) {
                showMessage('خطا در برقراری ارتباط: ' + error.message, 'danger');
            } finally {
                testOpenAIBtn.disabled = false;
                testOpenAIBtn.innerHTML = originalText;
            }
        });
    }
    
    // ارسال پیامک تست
    const sendTestSmsBtn = document.getElementById('send_test_sms');
    if (sendTestSmsBtn) {
        // پر کردن فیلد تست با شماره ذخیره شده در صورت خالی بودن
        const testSmsNumberField = document.getElementById('test_sms_number');
        const savedTestNumber = document.getElementById('sms_test_number');
        
        if (testSmsNumberField && savedTestNumber && !testSmsNumberField.value && savedTestNumber.value) {
            testSmsNumberField.value = savedTestNumber.value;
        }
        
        sendTestSmsBtn.addEventListener('click', async function() {
            const provider = document.getElementById('sms_provider').value;
            const username = document.getElementById('sms_username').value;
            const password = document.getElementById('sms_password').value;
            const sender = document.getElementById('sms_sender').value;
            const testNumber = document.getElementById('test_sms_number').value;
            
            if (!username || !password) {
                showMessage('لطفاً نام کاربری و رمز عبور را وارد کنید.', 'warning');
                return;
            }
            
            if (!testNumber) {
                showMessage('لطفاً شماره موبایل تست را وارد کنید.', 'warning');
                return;
            }
            
            // نمایش حالت در حال بارگذاری
            const originalText = sendTestSmsBtn.innerHTML;
            sendTestSmsBtn.disabled = true;
            sendTestSmsBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> در حال ارسال...';
            
            try {
                const response = await fetch(baseUrl + '/admin/settings/test-sms', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'provider=' + encodeURIComponent(provider) + 
                          '&username=' + encodeURIComponent(username) + 
                          '&password=' + encodeURIComponent(password) + 
                          '&sender=' + encodeURIComponent(sender) + 
                          '&test_number=' + encodeURIComponent(testNumber)
                });
                
                const result = await response.json();
                
                console.log('نتیجه تست پیامک:', result); // لاگ برای دیباگ
                
                if (result.success) {
                    showMessage(result.message, 'success');
                    
                    // نمایش جزئیات در صورت موجود بودن
                    if (result.details) {
                        console.log('جزئیات تست پیامک:', result.details);
                    }
                } else {
                    let errorMessage = result.message;
                    
                    // اضافه کردن کد خطا اگر موجود باشد
                    if (result.code) {
                        errorMessage += ' (کد خطا: ' + result.code + ')';
                    }
                    
                    showMessage(errorMessage, 'danger');
                    
                    // لاگ جزئیات خطا برای دیباگ
                    if (result.details) {
                        console.error('جزئیات خطای تست پیامک:', result.details);
                    }
                }
            } catch (error) {
                console.error('خطا در درخواست تست پیامک:', error);
                showMessage('خطا در برقراری ارتباط: ' + error.message, 'danger');
            } finally {
                sendTestSmsBtn.disabled = false;
                sendTestSmsBtn.innerHTML = originalText;
            }
        });
    }
});

/**
 * نمایش پیام به کاربر
 */
function showMessage(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + type + ' alert-dismissible fade show';
    alertDiv.innerHTML = message + 
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="بستن"></button>';
    
    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.appendChild(alertDiv);
    } else {
        const mainContent = document.querySelector('main');
        mainContent.insertBefore(alertDiv, mainContent.firstChild);
    }
    
    // خروج خودکار بعد از 5 ثانیه
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 300);
    }, 5000);
}
