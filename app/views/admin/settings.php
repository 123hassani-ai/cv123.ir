<?php
/**
 * فایل: /app/views/admin/settings.php
 * توضیحات: نمای تنظیمات سیستم
 */
?>

<div class="row mb-4">
    <div class="col">
        <h2 class="h4 mb-3">
            <i class="bi bi-gear me-2"></i>
            تنظیمات سیستم
        </h2>
        <p class="text-muted">در این بخش می‌توانید تنظیمات اصلی سیستم را مدیریت کنید.</p>
    </div>
</div>

<ul class="nav nav-tabs mb-4" id="settingsTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab" aria-controls="general-pane" aria-selected="true">
            <i class="bi bi-gear-wide-connected me-1"></i>
            تنظیمات عمومی
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="ai-tab" data-bs-toggle="tab" data-bs-target="#ai-pane" type="button" role="tab" aria-controls="ai-pane" aria-selected="false">
            <i class="bi bi-cpu me-1"></i>
            تنظیمات هوش مصنوعی
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="sms-tab" data-bs-toggle="tab" data-bs-target="#sms-pane" type="button" role="tab" aria-controls="sms-pane" aria-selected="false">
            <i class="bi bi-chat me-1"></i>
            تنظیمات پیامک
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer-pane" type="button" role="tab" aria-controls="footer-pane" aria-selected="false">
            <i class="bi bi-layout-text-window-reverse me-1"></i>
            محتوای فوتر
        </button>
    </li>
</ul>

<form action="<?php echo BASE_URL; ?>/admin/settings/save" method="POST" class="needs-validation" novalidate>
    <div class="tab-content" id="settingsTabContent">
        <!-- تنظیمات عمومی -->
        <div class="tab-pane fade show active" id="general-pane" role="tabpanel" aria-labelledby="general-tab">
            <div class="form-section">
                <h5 class="border-bottom pb-2">تنظیمات عمومی سایت</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="site_name" class="form-label">نام سایت</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo APP_NAME; ?>" required>
                        <div class="form-text">نام سایت که در عنوان صفحات نمایش داده می‌شود.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="site_url" class="form-label">آدرس سایت</label>
                        <input type="url" class="form-control" id="site_url" name="site_url" value="<?php echo APP_URL; ?>" required>
                        <div class="form-text">آدرس کامل سایت با پروتکل http یا https</div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="site_description" class="form-label">توضیحات سایت</label>
                    <textarea class="form-control" id="site_description" name="site_description" rows="2" required><?php echo APP_DESCRIPTION; ?></textarea>
                    <div class="form-text">این توضیحات در متادیتای سایت استفاده می‌شود.</div>
                </div>
                
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="debug_mode" name="debug_mode" <?php echo DEBUG_MODE ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="debug_mode">حالت خطایابی (Debug Mode)</label>
                    <div class="form-text">در حالت خطایابی، خطاهای PHP نمایش داده می‌شوند.</div>
                </div>
            </div>
        </div>
        
        <!-- تنظیمات هوش مصنوعی -->
        <div class="tab-pane fade" id="ai-pane" role="tabpanel" aria-labelledby="ai-tab">
            <div class="form-section">
                <h5 class="border-bottom pb-2">تنظیمات هوش مصنوعی OpenAI</h5>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    برای استفاده از قابلیت‌های هوش مصنوعی، باید کلید API از سایت <a href="https://platform.openai.com/" target="_blank">OpenAI</a> دریافت کنید.
                </div>
                
                <div class="mb-3">
                    <label for="openai_api_key" class="form-label">کلید API هوش مصنوعی</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="openai_api_key" name="openai_api_key" value="<?php echo isset($settings['openai_api_key']) ? $settings['openai_api_key'] : ''; ?>">
                        <button class="btn btn-outline-primary" type="button" id="test_openai">تست اتصال</button>
                    </div>
                    <div class="form-text">این کلید برای اتصال به سرویس OpenAI استفاده می‌شود.</div>
                </div>
                
                <div class="mb-3">
                    <label for="ai_model" class="form-label">مدل هوش مصنوعی</label>
                    <select class="form-select" id="ai_model" name="ai_model">
                        <option value="gpt-3.5-turbo" selected>GPT-3.5 Turbo</option>
                        <option value="gpt-4">GPT-4</option>
                        <option value="gpt-4-turbo">GPT-4 Turbo</option>
                    </select>
                    <div class="form-text">مدل مورد استفاده برای پردازش متن و مصاحبه</div>
                </div>
                
                <div class="mb-3">
                    <label for="ai_instructions" class="form-label">دستورالعمل‌های هوش مصنوعی</label>
                    <textarea class="form-control" id="ai_instructions" name="ai_instructions" rows="4">شما یک دستیار هوشمند استخدام برای مجتمع کامپیوتر یک دو سه هستید. به سوالات متقاضیان به صورت دقیق، مفید و مودبانه پاسخ دهید.</textarea>
                    <div class="form-text">این متن به عنوان راهنمایی برای هوش مصنوعی استفاده می‌شود.</div>
                </div>
                
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="enable_ai" name="enable_ai" checked>
                    <label class="form-check-label" for="enable_ai">فعال‌سازی قابلیت هوش مصنوعی</label>
                </div>
            </div>
        </div>
        
        <!-- تنظیمات پیامک -->
        <div class="tab-pane fade" id="sms-pane" role="tabpanel" aria-labelledby="sms-tab">
            <div class="form-section">
                <h5 class="border-bottom pb-2">تنظیمات سرویس پیامک</h5>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    برای استفاده از سرویس پیامک، باید در سامانه <a href="https://sms.ir/" target="_blank">SMS.ir</a> یا <a href="https://kavenegar.com/" target="_blank">کاوه‌نگار</a> ثبت‌نام کرده و اطلاعات دسترسی دریافت کنید.
                </div>
                
                <div class="mb-3">
                    <label for="sms_provider" class="form-label">سرویس‌دهنده پیامک</label>
                    <select class="form-select" id="sms_provider" name="sms_provider">
                        <option value="0098" selected>0098 (سرویس پیامک ۰۰۹۸)</option>
                        <option value="sms.ir">SMS.ir</option>
                        <option value="kavenegar">کاوه‌نگار</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="sms_username" class="form-label">نام کاربری</label>
                    <input type="text" class="form-control" id="sms_username" name="sms_username" value="<?php echo isset($settings['sms_username']) ? $settings['sms_username'] : ''; ?>">
                </div>
                
                <div class="mb-3">
                    <label for="sms_password" class="form-label">رمز عبور یا کلید API</label>
                    <input type="password" class="form-control" id="sms_password" name="sms_password" value="<?php echo isset($settings['sms_password']) ? $settings['sms_password'] : ''; ?>">
                </div>
                
                <div class="mb-3">
                    <label for="sms_sender" class="form-label">شماره فرستنده</label>
                    <input type="text" class="form-control" id="sms_sender" name="sms_sender" value="<?php echo isset($settings['sms_sender']) ? $settings['sms_sender'] : ''; ?>">
                    <div class="form-text">شماره اختصاصی یا خط خدماتی که پیامک‌ها از طریق آن ارسال می‌شوند.</div>
                </div>
                
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="enable_sms" name="enable_sms" checked>
                    <label class="form-check-label" for="enable_sms">فعال‌سازی سرویس پیامک</label>
                </div>
                
                <div class="mb-3">
                    <label for="sms_test_message" class="form-label">متن پیامک تست</label>
                    <textarea class="form-control" id="sms_test_message" name="sms_test_message" rows="2" placeholder="متن پیامک تست"><?php echo isset($settings['sms_test_message']) ? $settings['sms_test_message'] : 'تست ارسال پیامک از سامانه CV123'; ?></textarea>
                    <div class="form-text">متن پیامکی که برای تست سرویس ارسال می‌شود.</div>
                </div>
                
                <div class="mb-3">
                    <label for="sms_test_number" class="form-label">شماره موبایل تست</label>
                    <input type="text" class="form-control" id="sms_test_number" name="sms_test_number" value="<?php echo isset($settings['sms_test_number']) ? $settings['sms_test_number'] : '09123456789'; ?>" placeholder="مثال: 09121234567">
                    <div class="form-text">شماره موبایل پیش‌فرض برای تست سرویس پیامک.</div>
                </div>
                
                <div class="mb-3">
                    <label for="test_sms_number" class="form-label">شماره تلفن تست (موقت)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="test_sms_number" placeholder="مثال: 09121234567">
                        <button class="btn btn-outline-primary" type="button" id="send_test_sms">ارسال پیامک تست</button>
                    </div>
                    <div class="form-text">برای تست سرویس پیامک، شماره موبایل را وارد کرده و دکمه ارسال را بزنید.</div>
                </div>
                
                <!-- محل نمایش پیام‌ها -->
                <div id="messages-container"></div>
            </div>
        </div>
        
        <!-- محتوای فوتر -->
        <div class="tab-pane fade" id="footer-pane" role="tabpanel" aria-labelledby="footer-tab">
            <div class="form-section">
                <h5 class="border-bottom pb-2">محتوای فوتر سایت</h5>
                
                <div class="mb-3">
                    <label for="footer_text" class="form-label">متن فوتر</label>
                    <textarea class="form-control" id="footer_text" name="footer_text" rows="2"><?php echo isset($settings['footer_text']) ? $settings['footer_text'] : 'سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه'; ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="footer_address" class="form-label">آدرس</label>
                    <textarea class="form-control" id="footer_address" name="footer_address" rows="2"><?php echo isset($settings['footer_address']) ? $settings['footer_address'] : 'تهران، خیابان آزادی، مجتمع کامپیوتر یک دو سه'; ?></textarea>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="footer_phone" class="form-label">شماره تلفن</label>
                        <input type="text" class="form-control" id="footer_phone" name="footer_phone" value="<?php echo isset($settings['footer_phone']) ? $settings['footer_phone'] : '۰۲۱-۱۲۳۴۵۶۷۸'; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="footer_email" class="form-label">ایمیل</label>
                        <input type="email" class="form-control" id="footer_email" name="footer_email" value="<?php echo isset($settings['footer_email']) ? $settings['footer_email'] : 'info@cv123.ir'; ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="footer_instagram" class="form-label">آدرس اینستاگرام</label>
                        <div class="input-group">
                            <span class="input-group-text">instagram.com/</span>
                            <input type="text" class="form-control" id="footer_instagram" name="footer_instagram" value="cv123ir">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="footer_telegram" class="form-label">آدرس تلگرام</label>
                        <div class="input-group">
                            <span class="input-group-text">t.me/</span>
                            <input type="text" class="form-control" id="footer_telegram" name="footer_telegram" value="cv123ir">
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="footer_linkedin" class="form-label">آدرس لینکدین</label>
                        <div class="input-group">
                            <span class="input-group-text">linkedin.com/company/</span>
                            <input type="text" class="form-control" id="footer_linkedin" name="footer_linkedin" value="cv123ir">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="footer_twitter" class="form-label">آدرس توییتر</label>
                        <div class="input-group">
                            <span class="input-group-text">twitter.com/</span>
                            <input type="text" class="form-control" id="footer_twitter" name="footer_twitter" value="cv123ir">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>
            ذخیره تنظیمات
        </button>
        <button type="reset" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-counterclockwise me-1"></i>
            بازنشانی
        </button>
    </div>
</form>

<!-- افزودن اسکریپت تنظیمات -->
<script>
    // تعریف متغیر baseUrl برای استفاده در فایل settings.js
    const baseUrl = '<?php echo BASE_URL; ?>';
</script>
<script src="<?php echo BASE_URL; ?>/public/js/admin/settings.js"></script>
