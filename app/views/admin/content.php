<?php
/**
 * فایل: /app/views/admin/content.php
 * توضیحات: نمای مدیریت محتوای سایت
 */
?>

<div class="row mb-4">
    <div class="col">
        <h2 class="h4 mb-3">
            <i class="bi bi-file-earmark-text me-2"></i>
            مدیریت محتوای سایت
        </h2>
        <p class="text-muted">در این بخش می‌توانید محتوای صفحات اصلی سایت را مدیریت کنید.</p>
    </div>
</div>

<ul class="nav nav-tabs mb-4" id="contentTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-pane" type="button" role="tab" aria-controls="home-pane" aria-selected="true">
            <i class="bi bi-house me-1"></i>
            صفحه اصلی
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about-pane" type="button" role="tab" aria-controls="about-pane" aria-selected="false">
            <i class="bi bi-info-circle me-1"></i>
            درباره ما
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-pane" type="button" role="tab" aria-controls="contact-pane" aria-selected="false">
            <i class="bi bi-envelope me-1"></i>
            تماس با ما
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq-pane" type="button" role="tab" aria-controls="faq-pane" aria-selected="false">
            <i class="bi bi-question-circle me-1"></i>
            سوالات متداول
        </button>
    </li>
</ul>

<form action="<?php echo BASE_URL; ?>/admin/content/save" method="POST" class="needs-validation" novalidate>
    <div class="tab-content" id="contentTabContent">
        <!-- صفحه اصلی -->
        <div class="tab-pane fade show active" id="home-pane" role="tabpanel" aria-labelledby="home-tab">
            <div class="form-section">
                <h5 class="border-bottom pb-2">محتوای صفحه اصلی</h5>
                
                <div class="mb-3">
                    <label for="home_title" class="form-label">عنوان اصلی</label>
                    <input type="text" class="form-control" id="home_title" name="home_title" value="سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه" required>
                </div>
                
                <div class="mb-3">
                    <label for="home_subtitle" class="form-label">توضیحات کوتاه</label>
                    <textarea class="form-control" id="home_subtitle" name="home_subtitle" rows="2" required>رزومه خود را ارسال کنید و فرصت‌های شغلی مناسب را پیدا کنید.</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="home_banner" class="form-label">تصویر بنر</label>
                    <input class="form-control" type="file" id="home_banner" name="home_banner">
                    <div class="form-text">تصویر بنر اصلی صفحه خانه (سایز پیشنهادی: 1920×600 پیکسل)</div>
                </div>
                
                <div class="mb-3">
                    <label for="home_features" class="form-label">ویژگی‌های سیستم</label>
                    <div class="table-responsive">
                        <table class="table table-hover table-features">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th style="width: 100px">آیکون</th>
                                    <th>عنوان</th>
                                    <th>توضیحات</th>
                                    <th style="width: 100px">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <select class="form-select feature-icon" name="feature_icon[]">
                                            <option value="bi-lightning">رعد و برق</option>
                                            <option value="bi-gear" selected>چرخ دنده</option>
                                            <option value="bi-cpu">پردازنده</option>
                                            <option value="bi-graph-up">نمودار</option>
                                            <option value="bi-search">جستجو</option>
                                            <option value="bi-chat">چت</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="feature_title[]" value="سامانه هوشمند"></td>
                                    <td><input type="text" class="form-control" name="feature_desc[]" value="استفاده از هوش مصنوعی برای بررسی رزومه‌ها"></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger feature-delete"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <select class="form-select feature-icon" name="feature_icon[]">
                                            <option value="bi-lightning">رعد و برق</option>
                                            <option value="bi-gear">چرخ دنده</option>
                                            <option value="bi-cpu">پردازنده</option>
                                            <option value="bi-graph-up">نمودار</option>
                                            <option value="bi-search" selected>جستجو</option>
                                            <option value="bi-chat">چت</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="feature_title[]" value="جستجوی پیشرفته"></td>
                                    <td><input type="text" class="form-control" name="feature_desc[]" value="امکان جستجو بر اساس مهارت‌ها و تخصص‌ها"></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger feature-delete"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <select class="form-select feature-icon" name="feature_icon[]">
                                            <option value="bi-lightning">رعد و برق</option>
                                            <option value="bi-gear">چرخ دنده</option>
                                            <option value="bi-cpu">پردازنده</option>
                                            <option value="bi-graph-up">نمودار</option>
                                            <option value="bi-search">جستجو</option>
                                            <option value="bi-chat" selected>چت</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="feature_title[]" value="سیستم پیامکی"></td>
                                    <td><input type="text" class="form-control" name="feature_desc[]" value="اطلاع‌رسانی سریع از طریق پیامک"></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger feature-delete"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-sm btn-success w-100" id="add-feature">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            افزودن ویژگی جدید
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- درباره ما -->
        <div class="tab-pane fade" id="about-pane" role="tabpanel" aria-labelledby="about-tab">
            <div class="form-section">
                <h5 class="border-bottom pb-2">محتوای صفحه درباره ما</h5>
                
                <div class="mb-3">
                    <label for="about_title" class="form-label">عنوان صفحه</label>
                    <input type="text" class="form-control" id="about_title" name="about_title" value="درباره مجتمع کامپیوتر یک دو سه" required>
                </div>
                
                <div class="mb-3">
                    <label for="about_content" class="form-label">محتوای اصلی</label>
                    <textarea class="form-control editor" id="about_content" name="about_content" rows="10">
<h3>معرفی مجتمع کامپیوتر یک دو سه</h3>
<p>مجتمع کامپیوتر یک دو سه از سال 1380 فعالیت خود را در زمینه آموزش و خدمات کامپیوتری آغاز نموده است. هدف اصلی ما ارائه خدمات با کیفیت و گسترش دانش کامپیوتر در کشور عزیزمان ایران است.</p>

<h3>چشم‌انداز ما</h3>
<p>ما به دنبال ایجاد محیطی پویا برای رشد استعدادهای برتر و تربیت متخصصان حرفه‌ای در حوزه فناوری اطلاعات هستیم. تلاش ما این است که با بکارگیری روش‌های نوین آموزشی و استفاده از جدیدترین تکنولوژی‌ها، بهترین خدمات را به مشتریان خود ارائه دهیم.</p>

<h3>فرصت‌های شغلی</h3>
<p>مجتمع کامپیوتر یک دو سه همواره به دنبال جذب نیروهای متخصص و با انگیزه است. ما معتقدیم که نیروی انسانی، مهمترین سرمایه هر سازمان است و با ایجاد محیطی مناسب برای رشد و پیشرفت کارکنان، می‌توان به اهداف سازمانی دست یافت.</p>
                    </textarea>
                </div>
                
                <div class="mb-3">
                    <label for="about_image" class="form-label">تصویر صفحه درباره ما</label>
                    <input class="form-control" type="file" id="about_image" name="about_image">
                    <div class="form-text">تصویر نمایشی صفحه درباره ما (سایز پیشنهادی: 800×600 پیکسل)</div>
                </div>
            </div>
        </div>
        
        <!-- تماس با ما -->
        <div class="tab-pane fade" id="contact-pane" role="tabpanel" aria-labelledby="contact-tab">
            <div class="form-section">
                <h5 class="border-bottom pb-2">محتوای صفحه تماس با ما</h5>
                
                <div class="mb-3">
                    <label for="contact_title" class="form-label">عنوان صفحه</label>
                    <input type="text" class="form-control" id="contact_title" name="contact_title" value="تماس با مجتمع کامپیوتر یک دو سه" required>
                </div>
                
                <div class="mb-3">
                    <label for="contact_text" class="form-label">توضیحات کوتاه</label>
                    <textarea class="form-control" id="contact_text" name="contact_text" rows="3">برای ارتباط با ما، می‌توانید از طریق فرم زیر پیام خود را ارسال کنید. همچنین می‌توانید با شماره‌های اعلام شده تماس بگیرید یا به آدرس مجتمع مراجعه نمایید.</textarea>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contact_address" class="form-label">آدرس</label>
                        <textarea class="form-control" id="contact_address" name="contact_address" rows="2">تهران، خیابان آزادی، مجتمع کامپیوتر یک دو سه</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="contact_email" class="form-label">ایمیل</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" value="info@cv123.ir">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="contact_phone" class="form-label">تلفن</label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="۰۲۱-۱۲۳۴۵۶۷۸">
                    </div>
                    <div class="col-md-6">
                        <label for="contact_hours" class="form-label">ساعات کاری</label>
                        <input type="text" class="form-control" id="contact_hours" name="contact_hours" value="شنبه تا چهارشنبه، 9 الی 17">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="contact_map" class="form-label">کد نقشه گوگل</label>
                    <textarea class="form-control" id="contact_map" name="contact_map" rows="3"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3240.5487037621!2d51.33933!3d35.6997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f8e00a61f30034b%3A0xe59caa3900cd3c91!2z2K_Yp9mG2LTar9in2Ycg2KrZh9ix2KfZhg!5e0!3m2!1sen!2s!4v1651475888071!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></textarea>
                    <div class="form-text">کد iframe نقشه گوگل را در اینجا قرار دهید.</div>
                </div>
            </div>
        </div>
        
        <!-- سوالات متداول -->
        <div class="tab-pane fade" id="faq-pane" role="tabpanel" aria-labelledby="faq-tab">
            <div class="form-section">
                <h5 class="border-bottom pb-2">سوالات متداول</h5>
                
                <div class="mb-3">
                    <label for="faq_title" class="form-label">عنوان صفحه</label>
                    <input type="text" class="form-control" id="faq_title" name="faq_title" value="سوالات متداول" required>
                </div>
                
                <div class="mb-3">
                    <label for="faq_intro" class="form-label">توضیحات مقدماتی</label>
                    <textarea class="form-control" id="faq_intro" name="faq_intro" rows="2">در این بخش می‌توانید پاسخ سوالات متداول درباره فرآیند استخدام و نحوه کار با سامانه را مشاهده کنید.</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">لیست سوالات متداول</label>
                    <div class="table-responsive">
                        <table class="table table-hover table-faqs">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>سوال</th>
                                    <th>پاسخ</th>
                                    <th style="width: 100px">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><input type="text" class="form-control" name="faq_question[]" value="چگونه می‌توانم در سایت ثبت‌نام کنم؟"></td>
                                    <td><textarea class="form-control" name="faq_answer[]" rows="2">برای ثبت‌نام در سایت، از منوی بالای صفحه گزینه ثبت‌نام را انتخاب کنید. سپس فرم مربوطه را تکمیل کرده و بر روی دکمه ثبت‌نام کلیک کنید.</textarea></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger faq-delete"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><input type="text" class="form-control" name="faq_question[]" value="چگونه رزومه خود را ارسال کنم؟"></td>
                                    <td><textarea class="form-control" name="faq_answer[]" rows="2">پس از ورود به حساب کاربری، به بخش «ارسال رزومه» مراجعه کنید. در این بخش می‌توانید فایل رزومه خود را آپلود کرده و اطلاعات تکمیلی را وارد نمایید.</textarea></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger faq-delete"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td><input type="text" class="form-control" name="faq_question[]" value="چگونه از وضعیت درخواست خود مطلع شوم؟"></td>
                                    <td><textarea class="form-control" name="faq_answer[]" rows="2">پس از بررسی اولیه رزومه، از طریق ایمیل و پیامک به شما اطلاع‌رسانی خواهد شد. همچنین می‌توانید با مراجعه به پنل کاربری، وضعیت درخواست خود را مشاهده کنید.</textarea></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger faq-delete"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">
                                        <button type="button" class="btn btn-sm btn-success w-100" id="add-faq">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            افزودن سوال جدید
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>
            ذخیره محتوا
        </button>
        <button type="reset" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-counterclockwise me-1"></i>
            بازنشانی
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // اعتبارسنجی فرم
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
    
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
    
    // افزودن ویژگی جدید
    document.getElementById('add-feature').addEventListener('click', function() {
        const tbody = document.querySelector('.table-features tbody');
        const rowCount = tbody.rows.length;
        const newRow = document.createElement('tr');
        
        newRow.innerHTML = `
            <td>${rowCount + 1}</td>
            <td>
                <select class="form-select feature-icon" name="feature_icon[]">
                    <option value="bi-lightning">رعد و برق</option>
                    <option value="bi-gear">چرخ دنده</option>
                    <option value="bi-cpu">پردازنده</option>
                    <option value="bi-graph-up">نمودار</option>
                    <option value="bi-search">جستجو</option>
                    <option value="bi-chat">چت</option>
                </select>
            </td>
            <td><input type="text" class="form-control" name="feature_title[]" value=""></td>
            <td><input type="text" class="form-control" name="feature_desc[]" value=""></td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger feature-delete"><i class="bi bi-trash"></i></button>
            </td>
        `;
        
        tbody.appendChild(newRow);
        bindDeleteButtons();
    });
    
    // افزودن سوال جدید
    document.getElementById('add-faq').addEventListener('click', function() {
        const tbody = document.querySelector('.table-faqs tbody');
        const rowCount = tbody.rows.length;
        const newRow = document.createElement('tr');
        
        newRow.innerHTML = `
            <td>${rowCount + 1}</td>
            <td><input type="text" class="form-control" name="faq_question[]" value=""></td>
            <td><textarea class="form-control" name="faq_answer[]" rows="2"></textarea></td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger faq-delete"><i class="bi bi-trash"></i></button>
            </td>
        `;
        
        tbody.appendChild(newRow);
        bindDeleteButtons();
    });
    
    // حذف ردیف
    function bindDeleteButtons() {
        // حذف ویژگی
        document.querySelectorAll('.feature-delete').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('آیا از حذف این ویژگی اطمینان دارید؟')) {
                    const row = this.closest('tr');
                    row.remove();
                    updateRowNumbers('.table-features');
                }
            });
        });
        
        // حذف سوال
        document.querySelectorAll('.faq-delete').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('آیا از حذف این سوال اطمینان دارید؟')) {
                    const row = this.closest('tr');
                    row.remove();
                    updateRowNumbers('.table-faqs');
                }
            });
        });
    }
    
    // به‌روزرسانی شماره ردیف‌ها
    function updateRowNumbers(tableSelector) {
        const rows = document.querySelector(`${tableSelector} tbody`).rows;
        for (let i = 0; i < rows.length; i++) {
            rows[i].cells[0].textContent = i + 1;
        }
    }
    
    // فراخوانی اولیه
    bindDeleteButtons();
    
    // فعال‌سازی ویرایشگر متن پیشرفته
    // این قسمت در نسخه نهایی باید با یک ویرایشگر مانند TinyMCE یا CKEditor جایگزین شود
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '.editor',
            directionality: 'rtl',
            language: 'fa',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help'
        });
    }
});
</script>
