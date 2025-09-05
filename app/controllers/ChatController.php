<?php
/**
 * فایل: /app/controllers/ChatController.php
 * توضیحات: کنترلر چت هوشمند با OpenAI
 */

class ChatController
{
    /**
     * پردازش پیام‌های چت
     */
    public function sendMessage()
    {
        // بررسی درخواست AJAX
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // دریافت داده‌های ارسالی
            $message = isset($_POST['message']) ? trim($_POST['message']) : '';
            
            if (empty($message)) {
                $response = [
                    'status' => 'error',
                    'message' => 'پیام نمی‌تواند خالی باشد.'
                ];
                
                echo json_encode($response);
                exit;
            }
            
            // در نسخه اولیه، پاسخ ثابت برمی‌گردانیم
            $response = [
                'status' => 'success',
                'message' => 'سیستم چت هوشمند هنوز پیاده‌سازی نشده است. لطفاً بعداً مراجعه کنید.'
            ];
            
            echo json_encode($response);
            exit;
        } else {
            // اگر درخواست POST نیست، به صفحه اصلی هدایت می‌کنیم
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }
}
