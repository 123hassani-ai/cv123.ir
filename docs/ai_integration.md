# راهنمای اتصال به هوش مصنوعی OpenAI

این مستند، نحوه اتصال به سرویس هوش مصنوعی OpenAI و استفاده از آن در سامانه استخدام نیروی انسانی مجتمع کامپیوتر یک دو سه را شرح می‌دهد.

## معرفی OpenAI

OpenAI یک شرکت هوش مصنوعی است که مدل‌های پیشرفته پردازش زبان طبیعی مانند GPT را ارائه می‌دهد. در این پروژه، از API OpenAI برای ایجاد یک سیستم چت هوشمند استفاده می‌شود که می‌تواند:

- به سوالات متقاضیان درباره فرصت‌های شغلی پاسخ دهد
- در تکمیل فرم‌ها و رزومه به کاربران کمک کند
- توصیه‌های شغلی مناسب ارائه دهد
- به عنوان دستیار مجازی استخدام عمل کند
- مصاحبه‌های اولیه را انجام دهد

## پیش‌نیازها

برای استفاده از API OpenAI، به موارد زیر نیاز دارید:

1. **حساب کاربری** در [پلتفرم OpenAI](https://platform.openai.com/)
2. **کلید API** که از پنل کاربری OpenAI دریافت می‌شود
3. **دسترسی به اینترنت** برای ارتباط با سرورهای OpenAI

## تنظیمات اتصال

تنظیمات اتصال به OpenAI در فایل `config/config.php` قرار دارند:

```php
// تنظیمات OpenAI
define('OPENAI_API_KEY', '');  // کلید API هوش مصنوعی OpenAI
```

کلید API خود را در این قسمت وارد کنید.

## مدیریت کلید API از طریق پنل مدیریت

مدیریت کلید API و تنظیمات OpenAI از طریق بخش تنظیمات پنل مدیریت امکان‌پذیر است. برای این منظور:

1. وارد پنل مدیریت شوید: `{BASE_URL}/admin`
2. به بخش تنظیمات بروید: `{BASE_URL}/admin/settings`
3. به تب "تنظیمات هوش مصنوعی" بروید

در این بخش می‌توانید:
- کلید API را وارد کنید
- مدل هوش مصنوعی را انتخاب کنید (GPT-3.5 Turbo، GPT-4، و غیره)
- دستورالعمل‌های پیش‌فرض هوش مصنوعی را تنظیم کنید
- اتصال به API را تست کنید

### تست اتصال به OpenAI

برای اطمینان از صحت کلید API و اتصال به سرویس OpenAI، می‌توانید از قابلیت تست اتصال در پنل مدیریت استفاده کنید:

1. کلید API را وارد کنید
2. روی دکمه "تست اتصال" کلیک کنید
3. سیستم یک درخواست آزمایشی به OpenAI ارسال می‌کند و نتیجه را نمایش می‌دهد

کد پشت صحنه این قابلیت در کلاس `SettingsController` پیاده‌سازی شده است:

```php
/**
 * تست اتصال به OpenAI
 */
public function testOpenAI()
{
    // بررسی دسترسی مدیر
    $this->checkAdminAccess();
    
    // دریافت کلید API
    $apiKey = $_POST['api_key'] ?? '';
    
    if (empty($apiKey)) {
        echo json_encode([
            'success' => false,
            'message' => 'کلید API وارد نشده است.'
        ]);
        return;
    }
    
    // ذخیره موقت کلید API
    $this->saveTemporaryApiKey($apiKey);
    
    // تست اتصال
    try {
        $openai = new OpenAIService();
        $result = $openai->testConnection();
        
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'خطا در تست اتصال: ' . $e->getMessage()
        ]);
    }
}
```
    /**
     * سازنده کلاس
     */
    public function __construct()
    {
        $this->apiKey = OPENAI_API_KEY;
    }
    
    /**
     * ارسال پیام به OpenAI و دریافت پاسخ
     * 
     * @param string $message پیام کاربر
     * @param array $history تاریخچه گفتگو
     * @param array $options تنظیمات اضافی
     * @return array پاسخ OpenAI
     */
    public function chat($message, $history = [], $options = [])
    {
        // ساخت آرایه پیام‌ها
        $messages = [];
        
        // افزودن پیام سیستم برای راهنمایی مدل
        $systemMessage = isset($options['system_message']) ? $options['system_message'] : 
            'شما یک دستیار هوشمند استخدام برای مجتمع کامپیوتر یک دو سه هستید. به سوالات متقاضیان به صورت دقیق، مفید و مودبانه پاسخ دهید.';
        
        $messages[] = [
            'role' => 'system',
            'content' => $systemMessage
        ];
        
        // افزودن تاریخچه گفتگو
        if (!empty($history)) {
            foreach ($history as $entry) {
                $messages[] = [
                    'role' => 'user',
                    'content' => $entry['message']
                ];
                
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $entry['response']
                ];
            }
        }
        
        // افزودن پیام جدید کاربر
        $messages[] = [
            'role' => 'user',
            'content' => $message
        ];
        
        // تنظیمات درخواست
        $data = [
            'model' => isset($options['model']) ? $options['model'] : $this->model,
            'messages' => $messages,
            'temperature' => isset($options['temperature']) ? $options['temperature'] : 0.7,
            'max_tokens' => isset($options['max_tokens']) ? $options['max_tokens'] : 500,
            'top_p' => isset($options['top_p']) ? $options['top_p'] : 1.0,
            'frequency_penalty' => isset($options['frequency_penalty']) ? $options['frequency_penalty'] : 0,
            'presence_penalty' => isset($options['presence_penalty']) ? $options['presence_penalty'] : 0,
        ];
        
        // ارسال درخواست به API
        return $this->sendRequest('/chat/completions', $data);
    }
    
    /**
     * ارزیابی رزومه با استفاده از هوش مصنوعی
     * 
     * @param string $resumeText متن رزومه
     * @param string $jobDescription توضیحات شغل
     * @return array نتیجه ارزیابی
     */
    public function evaluateResume($resumeText, $jobDescription)
    {
        $message = "لطفاً این رزومه را برای موقعیت شغلی زیر ارزیابی کنید و امتیاز تناسب (از 0 تا 100)، نقاط قوت، نقاط ضعف و توصیه‌های بهبود را ارائه دهید.\n\nشرح شغل:\n$jobDescription\n\nرزومه:\n$resumeText";
        
        $options = [
            'system_message' => 'شما یک متخصص منابع انسانی با تجربه زیاد در استخدام هستید. وظیفه شما ارزیابی دقیق و عادلانه رزومه‌ها برای موقعیت‌های شغلی مختلف است.',
            'temperature' => 0.5,
            'max_tokens' => 1000
        ];
        
        $response = $this->chat($message, [], $options);
        
        if (!isset($response['success']) || $response['success'] !== true) {
            return [
                'success' => false,
                'message' => 'خطا در ارزیابی رزومه',
                'error' => $response['message'] ?? 'خطای نامشخص'
            ];
        }
        
        return [
            'success' => true,
            'evaluation' => $response['content']
        ];
    }
    
    /**
     * طراحی سوالات مصاحبه با استفاده از هوش مصنوعی
     * 
     * @param string $jobDescription توضیحات شغل
     * @param array $skills مهارت‌های مورد نیاز
     * @param int $count تعداد سوالات
     * @return array سوالات مصاحبه
     */
    public function generateInterviewQuestions($jobDescription, $skills = [], $count = 5)
    {
        $skillsText = !empty($skills) ? "\nمهارت‌های مورد نیاز: " . implode(', ', $skills) : '';
        
        $message = "لطفاً $count سوال مصاحبه مناسب برای موقعیت شغلی زیر طراحی کنید. سوالات باید ترکیبی از سوالات فنی، رفتاری و حل مسئله باشند.\n\nشرح شغل:\n$jobDescription$skillsText";
        
        $options = [
            'system_message' => 'شما یک متخصص استخدام هستید. وظیفه شما طراحی سوالات مصاحبه مناسب برای موقعیت‌های شغلی مختلف است.',
            'temperature' => 0.7,
            'max_tokens' => 1000
        ];
        
        $response = $this->chat($message, [], $options);
        
        if (!isset($response['success']) || $response['success'] !== true) {
            return [
                'success' => false,
                'message' => 'خطا در طراحی سوالات مصاحبه',
                'error' => $response['message'] ?? 'خطای نامشخص'
            ];
        }
        
        return [
            'success' => true,
            'questions' => $response['content']
        ];
    }
    
    /**
     * ارسال درخواست به API
     * 
     * @param string $endpoint نقطه پایانی API
     * @param array $data داده‌های درخواست
     * @return array پاسخ API
     */
    private function sendRequest($endpoint, $data)
    {
        $url = $this->apiUrl . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $error,
                'http_code' => $httpCode
            ];
        }
        
        $result = json_decode($response, true);
        
        if (!$result || $httpCode != 200) {
            return [
                'success' => false,
                'message' => 'API Error: ' . ($result['error']['message'] ?? 'Unknown error'),
                'http_code' => $httpCode,
                'response' => $response
            ];
        }
        
        return [
            'success' => true,
            'content' => $result['choices'][0]['message']['content'],
            'model' => $result['model'],
            'usage' => $result['usage'],
            'id' => $result['id']
        ];
    }
}
```

## کنترلر چت

کنترلر زیر برای مدیریت درخواست‌های چت در API استفاده می‌شود:

```php
<?php
/**
 * فایل: /app/controllers/ChatController.php
 * توضیحات: کنترلر برای مدیریت چت هوش مصنوعی
 */

class ChatController
{
    private $openAIService;
    private $chatModel;
    
    public function __construct()
    {
        $this->openAIService = new OpenAIService();
        $this->chatModel = new AIChat();
    }
    
    /**
     * ارسال پیام به هوش مصنوعی و ذخیره گفتگو
     */
    public function sendMessage()
    {
        // بررسی روش درخواست
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respondWithError('روش درخواست نامعتبر است.', 405);
            return;
        }
        
        // بررسی احراز هویت کاربر
        $auth = new Auth();
        if (!$auth->isLoggedIn()) {
            $this->respondWithError('لطفاً ابتدا وارد حساب کاربری خود شوید.', 401);
            return;
        }
        
        // دریافت داده‌های درخواست
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['message']) || empty($data['message'])) {
            $this->respondWithError('پیام نمی‌تواند خالی باشد.', 400);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        $message = trim($data['message']);
        
        // دریافت تاریخچه گفتگو
        $history = $this->chatModel->getRecentChats($userId, 5);
        
        try {
            // ارسال پیام به OpenAI
            $response = $this->openAIService->chat($message, $history);
            
            if (!isset($response['success']) || $response['success'] !== true) {
                $this->respondWithError('خطا در پردازش پیام: ' . ($response['message'] ?? 'خطای نامشخص'), 500);
                return;
            }
            
            $aiResponse = $response['content'];
            
            // ذخیره گفتگو در دیتابیس
            $chatId = $this->chatModel->saveChat($userId, $message, $aiResponse);
            
            if (!$chatId) {
                $this->respondWithError('خطا در ذخیره گفتگو.', 500);
                return;
            }
            
            // ارسال پاسخ موفق
            $this->respondWithSuccess([
                'message' => $message,
                'response' => $aiResponse,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            $this->respondWithError('خطای سیستمی: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * دریافت تاریخچه گفتگو
     */
    public function getHistory()
    {
        // بررسی روش درخواست
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $this->respondWithError('روش درخواست نامعتبر است.', 405);
            return;
        }
        
        // بررسی احراز هویت کاربر
        $auth = new Auth();
        if (!$auth->isLoggedIn()) {
            $this->respondWithError('لطفاً ابتدا وارد حساب کاربری خود شوید.', 401);
            return;
        }
        
        $userId = $_SESSION['user_id'];
        
        // پارامترهای صفحه‌بندی
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        
        if ($page < 1) $page = 1;
        if ($limit < 1 || $limit > 100) $limit = 20;
        
        // دریافت تاریخچه گفتگو
        $chats = $this->chatModel->getChats($userId, $page, $limit);
        $total = $this->chatModel->getChatsCount($userId);
        
        // ارسال پاسخ موفق
        $this->respondWithSuccess([
            'chats' => $chats,
            'pagination' => [
                'total' => $total,
                'per_page' => $limit,
                'current_page' => $page,
                'last_page' => ceil($total / $limit),
                'from' => ($page - 1) * $limit + 1,
                'to' => min($page * $limit, $total)
            ]
        ]);
    }
    
    /**
     * ارسال پاسخ موفق
     */
    private function respondWithSuccess($data)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
        exit;
    }
    
    /**
     * ارسال پاسخ خطا
     */
    private function respondWithError($message, $code = 400)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
}
```

## مدل چت

مدل زیر برای ذخیره و بازیابی گفتگوها در دیتابیس استفاده می‌شود:

```php
<?php
/**
 * فایل: /app/models/AIChat.php
 * توضیحات: مدل برای مدیریت گفتگوهای هوش مصنوعی
 */

class AIChat
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * ذخیره گفتگو در دیتابیس
     * 
     * @param int $userId شناسه کاربر
     * @param string $message پیام کاربر
     * @param string $response پاسخ هوش مصنوعی
     * @return int|bool شناسه گفتگو یا false در صورت خطا
     */
    public function saveChat($userId, $message, $response)
    {
        try {
            $this->db->query(
                "INSERT INTO ai_chats (user_id, message, response) VALUES (?, ?, ?)",
                [$userId, $message, $response]
            );
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Error saving chat: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * دریافت گفتگوهای اخیر کاربر
     * 
     * @param int $userId شناسه کاربر
     * @param int $limit تعداد گفتگوها
     * @return array لیست گفتگوها
     */
    public function getRecentChats($userId, $limit = 5)
    {
        try {
            $chats = $this->db->query(
                "SELECT message, response FROM ai_chats WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
                [$userId, $limit]
            )->fetchAll();
            
            // معکوس کردن ترتیب برای استفاده در تاریخچه
            return array_reverse($chats);
        } catch (Exception $e) {
            error_log("Error getting recent chats: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * دریافت گفتگوهای کاربر با صفحه‌بندی
     * 
     * @param int $userId شناسه کاربر
     * @param int $page شماره صفحه
     * @param int $limit تعداد آیتم در هر صفحه
     * @return array لیست گفتگوها
     */
    public function getChats($userId, $page = 1, $limit = 20)
    {
        $offset = ($page - 1) * $limit;
        
        try {
            return $this->db->query(
                "SELECT id, message, response, created_at FROM ai_chats WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
                [$userId, $limit, $offset]
            )->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting chats: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * دریافت تعداد کل گفتگوهای کاربر
     * 
     * @param int $userId شناسه کاربر
     * @return int تعداد کل گفتگوها
     */
    public function getChatsCount($userId)
    {
        try {
            $result = $this->db->query(
                "SELECT COUNT(*) as count FROM ai_chats WHERE user_id = ?",
                [$userId]
            )->fetch();
            
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            error_log("Error getting chats count: " . $e->getMessage());
            return 0;
        }
    }
}
```

## نمای چت

برای ایجاد رابط کاربری چت، از کد HTML و JavaScript زیر استفاده می‌شود:

```html
<!-- فایل: /app/views/user/chat.php -->
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">دستیار هوشمند استخدام</h5>
                </div>
                <div class="card-body">
                    <div class="chat-container" id="chatContainer">
                        <div class="chat-messages" id="chatMessages">
                            <div class="message assistant">
                                <div class="message-content">
                                    سلام! من دستیار هوشمند استخدام مجتمع کامپیوتر یک دو سه هستم. چگونه می‌توانم به شما کمک کنم؟
                                </div>
                                <div class="message-time">اکنون</div>
                            </div>
                        </div>
                        
                        <div class="chat-input">
                            <form id="chatForm">
                                <div class="input-group">
                                    <input type="text" id="messageInput" class="form-control" placeholder="پیام خود را بنویسید..." autocomplete="off">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 text-center text-muted small">
                <p>این چت با استفاده از هوش مصنوعی پاسخ می‌دهد و ممکن است در برخی موارد اشتباه کند.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessages = document.getElementById('chatMessages');
    
    // بارگذاری تاریخچه گفتگو
    loadChatHistory();
    
    // ارسال پیام
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // نمایش پیام کاربر
        appendMessage('user', message);
        
        // پاک کردن فیلد ورودی
        messageInput.value = '';
        
        // نمایش وضعیت در حال تایپ
        const typingIndicator = appendTypingIndicator();
        
        // ارسال پیام به سرور
        fetch('/api/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // حذف وضعیت در حال تایپ
            typingIndicator.remove();
            
            if (data.status === 'success') {
                // نمایش پاسخ هوش مصنوعی
                appendMessage('assistant', data.data.response);
            } else {
                // نمایش خطا
                appendMessage('error', data.message || 'خطایی رخ داده است.');
            }
            
            // اسکرول به پایین
            scrollToBottom();
        })
        .catch(error => {
            // حذف وضعیت در حال تایپ
            typingIndicator.remove();
            
            // نمایش خطا
            appendMessage('error', 'خطا در ارتباط با سرور.');
            console.error('Error:', error);
            
            // اسکرول به پایین
            scrollToBottom();
        });
    });
    
    // بارگذاری تاریخچه گفتگو
    function loadChatHistory() {
        fetch('/api/chat/history?limit=10')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.data.chats.length > 0) {
                    // پاک کردن پیام خوشامدگویی
                    chatMessages.innerHTML = '';
                    
                    // نمایش تاریخچه گفتگو
                    data.data.chats.forEach(chat => {
                        appendMessage('user', chat.message, formatDate(chat.created_at));
                        appendMessage('assistant', chat.response, formatDate(chat.created_at));
                    });
                    
                    // اسکرول به پایین
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('Error loading chat history:', error);
            });
    }
    
    // افزودن پیام به صفحه
    function appendMessage(sender, content, time = null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.innerHTML = formatMessage(content);
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = time || 'اکنون';
        
        messageDiv.appendChild(contentDiv);
        messageDiv.appendChild(timeDiv);
        
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
        
        return messageDiv;
    }
    
    // افزودن وضعیت در حال تایپ
    function appendTypingIndicator() {
        const indicatorDiv = document.createElement('div');
        indicatorDiv.className = 'message assistant typing';
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = 'در حال تایپ...';
        
        indicatorDiv.appendChild(contentDiv);
        indicatorDiv.appendChild(timeDiv);
        
        chatMessages.appendChild(indicatorDiv);
        scrollToBottom();
        
        return indicatorDiv;
    }
    
    // اسکرول به پایین صفحه چت
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // فرمت کردن متن پیام (تبدیل لینک‌ها، ایموجی‌ها و...)
    function formatMessage(text) {
        // تبدیل URL ها به لینک
        text = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
        
        // تبدیل خطوط جدید به <br>
        text = text.replace(/\n/g, '<br>');
        
        return text;
    }
    
    // فرمت کردن تاریخ
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString('fa-IR', { 
            hour: '2-digit', 
            minute: '2-digit',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    }
});
</script>

<style>
.chat-container {
    display: flex;
    flex-direction: column;
    height: 500px;
    border-radius: 0.25rem;
    overflow: hidden;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    background-color: #f8f9fa;
}

.chat-input {
    padding: 0.5rem 1rem;
    background-color: #fff;
    border-top: 1px solid #e9ecef;
}

.message {
    margin-bottom: 1rem;
    max-width: 80%;
    display: flex;
    flex-direction: column;
}

.message.user {
    margin-left: auto;
}

.message.assistant {
    margin-right: auto;
}

.message.error {
    margin-left: auto;
    margin-right: auto;
}

.message-content {
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    word-break: break-word;
}

.user .message-content {
    background-color: #0d6efd;
    color: white;
    border-bottom-right-radius: 0.25rem;
}

.assistant .message-content {
    background-color: #e9ecef;
    color: #212529;
    border-bottom-left-radius: 0.25rem;
}

.error .message-content {
    background-color: #dc3545;
    color: white;
    text-align: center;
}

.message-time {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
    align-self: flex-end;
}

.assistant .message-time {
    align-self: flex-start;
}

.typing-indicator {
    display: inline-flex;
    align-items: center;
}

.typing-indicator span {
    height: 8px;
    width: 8px;
    background-color: #6c757d;
    border-radius: 50%;
    display: inline-block;
    margin: 0 1px;
    animation: bounce 1.3s linear infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.15s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.3s;
}

@keyframes bounce {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-5px);
    }
}
</style>
```

## موارد استفاده

### 1. مصاحبه اولیه هوشمند

```php
/**
 * انجام مصاحبه اولیه با استفاده از هوش مصنوعی
 * 
 * @param int $applicationId شناسه درخواست
 * @return array نتیجه مصاحبه
 */
public function conductInitialInterview($applicationId)
{
    // دریافت اطلاعات درخواست
    $application = $this->applicationModel->getById($applicationId);
    if (!$application) {
        return [
            'success' => false,
            'message' => 'درخواست مورد نظر یافت نشد.'
        ];
    }
    
    // دریافت اطلاعات شغل
    $job = $this->jobModel->getById($application['job_id']);
    
    // دریافت اطلاعات کاربر
    $user = $this->userModel->getById($application['user_id']);
    $profile = $this->profileModel->getByUserId($application['user_id']);
    
    // دریافت مهارت‌های کاربر
    $userSkills = $this->skillModel->getUserSkills($application['user_id']);
    $skillsText = '';
    foreach ($userSkills as $skill) {
        $skillsText .= "- {$skill['name']} (سطح {$skill['level']} از 5)\n";
    }
    
    // دریافت تجربیات کاری کاربر
    $workExperiences = $this->workExperienceModel->getByUserId($application['user_id']);
    $experiencesText = '';
    foreach ($workExperiences as $exp) {
        $experiencesText .= "- {$exp['position']} در {$exp['company']} از {$exp['start_date']} تا " . ($exp['current_job'] ? 'اکنون' : $exp['end_date']) . "\n";
    }
    
    // ایجاد پیام برای هوش مصنوعی
    $message = "لطفاً یک مصاحبه اولیه با این متقاضی انجام دهید و نتیجه را گزارش کنید. تناسب برای شغل، نقاط قوت، نقاط ضعف و توصیه‌ها را ذکر کنید.\n\n";
    $message .= "شغل: {$job['title']}\n\n";
    $message .= "شرح شغل: {$job['description']}\n\n";
    $message .= "نیازمندی‌ها: {$job['requirements']}\n\n";
    $message .= "اطلاعات متقاضی:\n";
    $message .= "- نام: {$user['name']}\n";
    $message .= "- تحصیلات: {$profile['education']} در رشته {$profile['field_of_study']} از {$profile['university']}\n\n";
    $message .= "مهارت‌ها:\n{$skillsText}\n";
    $message .= "تجربیات کاری:\n{$experiencesText}\n";
    $message .= "درباره متقاضی: {$profile['about']}\n";
    
    // تنظیمات هوش مصنوعی
    $options = [
        'system_message' => 'شما یک متخصص منابع انسانی هستید که مصاحبه‌های اولیه را انجام می‌دهد. وظیفه شما ارزیابی تناسب متقاضی برای موقعیت شغلی، شناسایی نقاط قوت و ضعف، و ارائه توصیه‌هایی برای مصاحبه‌های بعدی است.',
        'temperature' => 0.5,
        'max_tokens' => 1000
    ];
    
    // ارسال به هوش مصنوعی
    $openAIService = new OpenAIService();
    $response = $openAIService->chat($message, [], $options);
    
    if (!isset($response['success']) || $response['success'] !== true) {
        return [
            'success' => false,
            'message' => 'خطا در انجام مصاحبه: ' . ($response['message'] ?? 'خطای نامشخص')
        ];
    }
    
    // ذخیره نتیجه مصاحبه
    $this->applicationModel->updateAdminNotes($applicationId, $response['content']);
    
    return [
        'success' => true,
        'interview_result' => $response['content']
    ];
}
```

### 2. پیشنهاد شغل مناسب

```php
/**
 * پیشنهاد شغل مناسب با استفاده از هوش مصنوعی
 * 
 * @param int $userId شناسه کاربر
 * @return array لیست شغل‌های پیشنهادی
 */
public function recommendJobs($userId)
{
    // دریافت اطلاعات کاربر
    $user = $this->userModel->getById($userId);
    $profile = $this->profileModel->getByUserId($userId);
    
    // دریافت مهارت‌های کاربر
    $userSkills = $this->skillModel->getUserSkills($userId);
    $skillsText = '';
    foreach ($userSkills as $skill) {
        $skillsText .= "- {$skill['name']} (سطح {$skill['level']} از 5)\n";
    }
    
    // دریافت تجربیات کاری کاربر
    $workExperiences = $this->workExperienceModel->getByUserId($userId);
    $experiencesText = '';
    foreach ($workExperiences as $exp) {
        $experiencesText .= "- {$exp['position']} در {$exp['company']} از {$exp['start_date']} تا " . ($exp['current_job'] ? 'اکنون' : $exp['end_date']) . "\n";
        $experiencesText .= "  توضیحات: {$exp['description']}\n";
    }
    
    // دریافت لیست شغل‌های فعال
    $jobs = $this->jobModel->getActiveJobs();
    $jobsText = '';
    foreach ($jobs as $job) {
        $jobsText .= "شناسه: {$job['id']}\n";
        $jobsText .= "عنوان: {$job['title']}\n";
        $jobsText .= "دسته‌بندی: {$job['category']}\n";
        $jobsText .= "نوع: {$job['type']}\n";
        $jobsText .= "موقعیت: {$job['city']}، {$job['province']}\n";
        $jobsText .= "شرح: {$job['description']}\n";
        $jobsText .= "نیازمندی‌ها: {$job['requirements']}\n";
        $jobsText .= "---\n";
    }
    
    // ایجاد پیام برای هوش مصنوعی
    $message = "لطفاً با توجه به پروفایل این کاربر، مناسب‌ترین شغل‌ها را از لیست زیر پیشنهاد دهید. برای هر شغل، دلیل پیشنهاد و میزان تناسب (درصد) را ذکر کنید.\n\n";
    $message .= "اطلاعات کاربر:\n";
    $message .= "- نام: {$user['name']}\n";
    $message .= "- تحصیلات: {$profile['education']} در رشته {$profile['field_of_study']} از {$profile['university']}\n\n";
    $message .= "مهارت‌ها:\n{$skillsText}\n";
    $message .= "تجربیات کاری:\n{$experiencesText}\n";
    $message .= "درباره کاربر: {$profile['about']}\n\n";
    $message .= "لیست شغل‌های فعال:\n{$jobsText}\n";
    $message .= "لطفاً فقط شناسه شغل‌های پیشنهادی و دلیل پیشنهاد را به صورت ساختاریافته برگردانید.";
    
    // تنظیمات هوش مصنوعی
    $options = [
        'system_message' => 'شما یک متخصص کاریابی هستید. وظیفه شما تحلیل پروفایل کاربران و پیشنهاد مناسب‌ترین شغل‌ها با ذکر دلیل و میزان تناسب است.',
        'temperature' => 0.3,
        'max_tokens' => 1000
    ];
    
    // ارسال به هوش مصنوعی
    $openAIService = new OpenAIService();
    $response = $openAIService->chat($message, [], $options);
    
    if (!isset($response['success']) || $response['success'] !== true) {
        return [
            'success' => false,
            'message' => 'خطا در پیشنهاد شغل: ' . ($response['message'] ?? 'خطای نامشخص')
        ];
    }
    
    return [
        'success' => true,
        'recommendations' => $response['content']
    ];
}
```

## تست اتصال به OpenAI

برای تست اتصال به OpenAI، یک اسکریپت تست در پوشه `tests` ایجاد کنید:

```php
<?php
/**
 * فایل: /tests/openai_test.php
 * توضیحات: اسکریپت تست اتصال به OpenAI
 */

// بارگذاری تنظیمات
require_once __DIR__ . '/../config/config.php';

// بارگذاری کلاس OpenAIService
require_once __DIR__ . '/../app/helpers/OpenAIService.php';

// ایجاد نمونه از کلاس OpenAIService
$openAIService = new OpenAIService();

// تست چت ساده
echo "Testing simple chat...\n";
$message = "سلام، شما چه کسی هستید؟";
$response = $openAIService->chat($message);
print_r($response);
echo "\n";

// تست ارزیابی رزومه
echo "Testing resume evaluation...\n";
$resumeText = "نام: محمد محمدی\nتحصیلات: کارشناسی ارشد مهندسی کامپیوتر\nمهارت‌ها: PHP, JavaScript, MySQL, HTML, CSS\nتجربیات: 3 سال برنامه‌نویسی وب";
$jobDescription = "برنامه‌نویس Full Stack با تجربه در PHP و JavaScript";
$response = $openAIService->evaluateResume($resumeText, $jobDescription);
print_r($response);
echo "\n";

// تست طراحی سوالات مصاحبه
echo "Testing interview questions generation...\n";
$jobDescription = "برنامه‌نویس Full Stack با تجربه در PHP و JavaScript";
$skills = ["PHP", "JavaScript", "MySQL", "HTML", "CSS"];
$response = $openAIService->generateInterviewQuestions($jobDescription, $skills, 3);
print_r($response);
```

برای اجرای این اسکریپت، دستور زیر را در ترمینال وارد کنید:

```bash
php /Applications/XAMPP/xamppfiles/htdocs/cv123.ir/tests/openai_test.php
```

## عیب‌یابی

### مشکل: خطای کلید API نامعتبر

**راه حل**: کلید API را در پنل کاربری OpenAI بررسی کنید و اطمینان حاصل کنید که معتبر و فعال است.

### مشکل: خطای اتصال به API

**راه حل**: بررسی کنید که سرور به اینترنت دسترسی دارد و می‌تواند به سرورهای OpenAI متصل شود.

### مشکل: پاسخ‌های نامناسب یا بی‌ربط

**راه حل**: پیام system را بهبود دهید و راهنمایی‌های دقیق‌تری برای مدل فراهم کنید. همچنین می‌توانید پارامتر temperature را کاهش دهید تا پاسخ‌های دقیق‌تری دریافت کنید.

### مشکل: مصرف زیاد توکن و هزینه بالا

**راه حل**: پارامتر max_tokens را محدود کنید و تاریخچه گفتگو را کوتاه نگه دارید. همچنین می‌توانید از مدل‌های کوچک‌تر استفاده کنید.

## امنیت و حریم خصوصی

1. **محافظت از کلید API**: کلید API را در فایل‌های عمومی قرار ندهید و از متغیرهای محیطی یا فایل‌های محافظت‌شده استفاده کنید.

2. **محدودیت دسترسی**: دسترسی به API را محدود کنید و از احراز هویت کاربران اطمینان حاصل کنید.

3. **حفظ حریم خصوصی**: از ارسال اطلاعات شخصی حساس به API خودداری کنید.

4. **نظارت بر محتوا**: پاسخ‌های دریافتی از API را قبل از نمایش به کاربر بررسی کنید.

5. **ثبت فعالیت‌ها**: تمام درخواست‌ها و پاسخ‌های API را برای اهداف امنیتی و عیب‌یابی ثبت کنید.

## منابع و مراجع

- [مستندات رسمی OpenAI API](https://platform.openai.com/docs/)
- [مدل‌های OpenAI](https://platform.openai.com/docs/models)
- [راهنمای قیمت‌گذاری OpenAI](https://openai.com/pricing)
- [راهنمای استفاده از OpenAI در PHP](https://github.com/openai-php/client)
- [بهترین روش‌های استفاده از GPT](https://platform.openai.com/docs/guides/gpt-best-practices)
