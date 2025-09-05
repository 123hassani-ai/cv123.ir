# مستندات API سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه

این مستند، API های موجود در سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه را توضیح می‌دهد.

## اطلاعات کلی API

- **URL پایه**: `https://cv123.ir/api`
- **فرمت پاسخ**: JSON
- **احراز هویت**: Token-based (JWT)
- **نسخه API**: v1

## احراز هویت

تمام درخواست‌های API (به جز موارد ذکر شده) باید با توکن JWT احراز هویت شوند. توکن JWT باید در هدر `Authorization` به صورت زیر ارسال شود:

```
Authorization: Bearer <token>
```

### دریافت توکن

```
POST /auth/login
```

#### پارامترها

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| email | string | بله | ایمیل کاربر |
| password | string | بله | رمز عبور کاربر |

#### پاسخ موفق

```json
{
    "status": "success",
    "data": {
        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "user": {
            "id": 1,
            "name": "نام کاربر",
            "email": "user@example.com",
            "role": "user"
        }
    }
}
```

#### پاسخ خطا

```json
{
    "status": "error",
    "message": "نام کاربری یا رمز عبور اشتباه است."
}
```

## API های کاربر

### ثبت‌نام کاربر

```
POST /auth/register
```

#### پارامترها

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| name | string | بله | نام کامل کاربر |
| email | string | بله | ایمیل کاربر (باید یکتا باشد) |
| phone | string | بله | شماره تلفن کاربر |
| password | string | بله | رمز عبور کاربر (حداقل 8 کاراکتر) |
| password_confirmation | string | بله | تکرار رمز عبور |

#### پاسخ موفق

```json
{
    "status": "success",
    "message": "ثبت‌نام با موفقیت انجام شد. لطفاً ایمیل خود را تأیید کنید.",
    "data": {
        "user": {
            "id": 1,
            "name": "نام کاربر",
            "email": "user@example.com",
            "role": "user"
        }
    }
}
```

#### پاسخ خطا

```json
{
    "status": "error",
    "message": "خطا در ثبت‌نام.",
    "errors": {
        "email": ["این ایمیل قبلاً ثبت شده است."],
        "password": ["رمز عبور باید حداقل 8 کاراکتر باشد."]
    }
}
```

### دریافت اطلاعات کاربر

```
GET /user/profile
```

#### هدرها

| نام | مقدار |
|-----|-------|
| Authorization | Bearer <token> |

#### پاسخ موفق

```json
{
    "status": "success",
    "data": {
        "user": {
            "id": 1,
            "name": "نام کاربر",
            "email": "user@example.com",
            "phone": "09123456789",
            "role": "user",
            "created_at": "2023-01-01 12:00:00",
            "profile": {
                "national_code": "0123456789",
                "birth_date": "1990-01-01",
                "gender": "male",
                "province": "تهران",
                "city": "تهران",
                "address": "آدرس کاربر",
                "education": "کارشناسی",
                "field_of_study": "مهندسی کامپیوتر",
                "university": "دانشگاه تهران"
            }
        }
    }
}
```

### به‌روزرسانی اطلاعات کاربر

```
PUT /user/profile
```

#### هدرها

| نام | مقدار |
|-----|-------|
| Authorization | Bearer <token> |
| Content-Type | application/json |

#### پارامترها

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| name | string | خیر | نام کامل کاربر |
| phone | string | خیر | شماره تلفن کاربر |
| national_code | string | خیر | کد ملی |
| birth_date | string | خیر | تاریخ تولد (YYYY-MM-DD) |
| gender | string | خیر | جنسیت (male/female) |
| province | string | خیر | استان |
| city | string | خیر | شهر |
| address | string | خیر | آدرس |
| education | string | خیر | تحصیلات |
| field_of_study | string | خیر | رشته تحصیلی |
| university | string | خیر | دانشگاه |

#### پاسخ موفق

```json
{
    "status": "success",
    "message": "اطلاعات با موفقیت به‌روزرسانی شد.",
    "data": {
        "user": {
            "id": 1,
            "name": "نام جدید کاربر",
            "email": "user@example.com",
            "phone": "09123456789",
            "profile": {
                "national_code": "0123456789",
                "birth_date": "1990-01-01",
                "gender": "male",
                "province": "تهران",
                "city": "تهران"
            }
        }
    }
}
```

### آپلود رزومه

```
POST /user/resume
```

#### هدرها

| نام | مقدار |
|-----|-------|
| Authorization | Bearer <token> |
| Content-Type | multipart/form-data |

#### پارامترها

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| resume | file | بله | فایل رزومه (PDF/DOC/DOCX، حداکثر 5MB) |

#### پاسخ موفق

```json
{
    "status": "success",
    "message": "رزومه با موفقیت آپلود شد.",
    "data": {
        "resume_url": "/uploads/resumes/user_1_resume.pdf"
    }
}
```

## API های مهارت‌ها

### دریافت لیست مهارت‌ها

```
GET /skills
```

#### پارامترهای Query

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| category | string | خیر | فیلتر بر اساس دسته‌بندی |
| search | string | خیر | جستجو در نام مهارت |

#### پاسخ موفق

```json
{
    "status": "success",
    "data": {
        "skills": [
            {
                "id": 1,
                "name": "PHP",
                "category": "programming"
            },
            {
                "id": 2,
                "name": "JavaScript",
                "category": "programming"
            },
            {
                "id": 3,
                "name": "MySQL",
                "category": "database"
            }
        ]
    }
}
```

### افزودن مهارت به پروفایل کاربر

```
POST /user/skills
```

#### هدرها

| نام | مقدار |
|-----|-------|
| Authorization | Bearer <token> |
| Content-Type | application/json |

#### پارامترها

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| skill_id | int | بله | شناسه مهارت |
| level | int | بله | سطح مهارت (1-5) |

#### پاسخ موفق

```json
{
    "status": "success",
    "message": "مهارت با موفقیت به پروفایل شما اضافه شد.",
    "data": {
        "skill": {
            "id": 1,
            "name": "PHP",
            "category": "programming",
            "level": 4
        }
    }
}
```

## API های موقعیت‌های شغلی

### دریافت لیست موقعیت‌های شغلی

```
GET /jobs
```

#### پارامترهای Query

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| category | string | خیر | فیلتر بر اساس دسته‌بندی |
| province | string | خیر | فیلتر بر اساس استان |
| city | string | خیر | فیلتر بر اساس شهر |
| type | string | خیر | فیلتر بر اساس نوع همکاری |
| search | string | خیر | جستجو در عنوان و توضیحات |
| page | int | خیر | شماره صفحه (پیش‌فرض: 1) |
| limit | int | خیر | تعداد آیتم در هر صفحه (پیش‌فرض: 10) |

#### پاسخ موفق

```json
{
    "status": "success",
    "data": {
        "jobs": [
            {
                "id": 1,
                "title": "برنامه‌نویس PHP",
                "description": "توضیحات شغل...",
                "type": "full-time",
                "province": "تهران",
                "city": "تهران",
                "category": "programming",
                "created_at": "2023-01-01 12:00:00"
            },
            {
                "id": 2,
                "title": "طراح UI/UX",
                "description": "توضیحات شغل...",
                "type": "remote",
                "province": "اصفهان",
                "city": "اصفهان",
                "category": "design",
                "created_at": "2023-01-02 12:00:00"
            }
        ],
        "pagination": {
            "total": 25,
            "per_page": 10,
            "current_page": 1,
            "last_page": 3,
            "from": 1,
            "to": 10
        }
    }
}
```

### دریافت جزئیات موقعیت شغلی

```
GET /jobs/{id}
```

#### پاسخ موفق

```json
{
    "status": "success",
    "data": {
        "job": {
            "id": 1,
            "title": "برنامه‌نویس PHP",
            "description": "توضیحات کامل شغل...",
            "requirements": "شرایط لازم...",
            "benefits": "مزایا...",
            "type": "full-time",
            "min_salary": 10000000,
            "max_salary": 15000000,
            "province": "تهران",
            "city": "تهران",
            "category": "programming",
            "created_at": "2023-01-01 12:00:00",
            "skills": [
                {
                    "id": 1,
                    "name": "PHP",
                    "is_required": true
                },
                {
                    "id": 2,
                    "name": "JavaScript",
                    "is_required": false
                }
            ]
        }
    }
}
```

### ارسال درخواست برای موقعیت شغلی

```
POST /jobs/{id}/apply
```

#### هدرها

| نام | مقدار |
|-----|-------|
| Authorization | Bearer <token> |
| Content-Type | application/json |

#### پارامترها

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| cover_letter | string | خیر | نامه معرفی |

#### پاسخ موفق

```json
{
    "status": "success",
    "message": "درخواست شما با موفقیت ثبت شد.",
    "data": {
        "application": {
            "id": 1,
            "job_id": 1,
            "user_id": 1,
            "status": "pending",
            "created_at": "2023-01-10 12:00:00"
        }
    }
}
```

## API های چت هوش مصنوعی

### ارسال پیام به هوش مصنوعی

```
POST /chat
```

#### هدرها

| نام | مقدار |
|-----|-------|
| Authorization | Bearer <token> |
| Content-Type | application/json |

#### پارامترها

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| message | string | بله | پیام کاربر |

#### پاسخ موفق

```json
{
    "status": "success",
    "data": {
        "message": "پیام کاربر",
        "response": "پاسخ هوش مصنوعی...",
        "created_at": "2023-01-10 12:00:00"
    }
}
```

### دریافت تاریخچه چت

```
GET /chat/history
```

#### هدرها

| نام | مقدار |
|-----|-------|
| Authorization | Bearer <token> |

#### پارامترهای Query

| نام | نوع | اجباری | توضیحات |
|-----|-----|--------|---------|
| page | int | خیر | شماره صفحه (پیش‌فرض: 1) |
| limit | int | خیر | تعداد آیتم در هر صفحه (پیش‌فرض: 20) |

#### پاسخ موفق

```json
{
    "status": "success",
    "data": {
        "chats": [
            {
                "id": 1,
                "message": "سلام",
                "response": "سلام، چطور می‌توانم کمک کنم؟",
                "created_at": "2023-01-10 12:00:00"
            },
            {
                "id": 2,
                "message": "چگونه رزومه خوبی بنویسم؟",
                "response": "برای نوشتن یک رزومه خوب...",
                "created_at": "2023-01-10 12:05:00"
            }
        ],
        "pagination": {
            "total": 10,
            "per_page": 20,
            "current_page": 1,
            "last_page": 1,
            "from": 1,
            "to": 10
        }
    }
}
```

## کدهای وضعیت HTTP

| کد | توضیحات |
|----|---------|
| 200 | درخواست با موفقیت انجام شد |
| 201 | منبع با موفقیت ایجاد شد |
| 400 | درخواست نامعتبر است |
| 401 | احراز هویت الزامی است |
| 403 | دسترسی غیرمجاز |
| 404 | منبع یافت نشد |
| 422 | داده‌های ارسالی نامعتبر هستند |
| 429 | تعداد درخواست‌ها بیش از حد مجاز است |
| 500 | خطای داخلی سرور |

## محدودیت‌های API

- حداکثر 60 درخواست در دقیقه برای هر کاربر
- حداکثر اندازه درخواست: 10MB
- فرمت‌های فایل مجاز برای آپلود:
  - رزومه: PDF, DOC, DOCX (حداکثر 5MB)
  - عکس پروفایل: JPG, PNG, GIF (حداکثر 2MB)

## نمونه کد استفاده از API

### PHP

```php
<?php
$apiUrl = 'https://cv123.ir/api/auth/login';
$data = [
    'email' => 'user@example.com',
    'password' => 'password123'
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
print_r($result);
```

### JavaScript

```javascript
fetch('https://cv123.ir/api/auth/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'user@example.com',
        password: 'password123'
    })
})
.then(response => response.json())
.then(data => console.log(data))
.catch(error => console.error('Error:', error));
```
