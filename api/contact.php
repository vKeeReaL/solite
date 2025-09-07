<?php
/**
 * Contact Form API Endpoint
 * 
 * Handles contact form submissions and sends notifications to Telegram
 */

require_once 'config.php';

// Set CORS headers
setCorsHeaders();
handlePreflight();

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendErrorResponse('Method not allowed', 405);
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Rate limiting check
$client_ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!checkRateLimit($client_ip, 5, 3600)) { // 5 requests per hour
    sendErrorResponse('Too many requests. Please try again later.', 429);
}

// Validate required fields
$required_fields = ['firstName', 'lastName', 'email', 'subject', 'message'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    sendErrorResponse('Missing required fields: ' . implode(', ', $missing_fields));
}

// Validate email
if (!validateEmail($input['email'])) {
    sendErrorResponse('Invalid email format');
}

// Sanitize input
$firstName = sanitizeInput($input['firstName']);
$lastName = sanitizeInput($input['lastName']);
$email = sanitizeInput($input['email']);
$subject = sanitizeInput($input['subject']);
$message = sanitizeInput($input['message']);

// Get Telegram configuration
$telegram_config = getTelegramConfig();
$bot_token = $telegram_config['bot_token'];
$chat_id = $telegram_config['chat_id'];

// Format message for Telegram
$telegram_message = "🕯️ *New Contact Form Submission*\n\n";
$telegram_message .= "👤 *Name:* {$firstName} {$lastName}\n";
$telegram_message .= "📧 *Email:* {$email}\n";
$telegram_message .= "📝 *Subject:* {$subject}\n";
$telegram_message .= "💬 *Message:*\n{$message}\n\n";
$telegram_message .= "⏰ *Time:* " . date('Y-m-d H:i:s') . "\n";
$telegram_message .= "🌐 *Website:* Solite Candles";

// Send to Telegram
$telegram_url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
$telegram_data = [
    'chat_id' => $chat_id,
    'text' => $telegram_message,
    'parse_mode' => 'Markdown'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegram_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($telegram_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Check if Telegram message was sent successfully
if ($http_code === 200 && $response) {
    $telegram_response = json_decode($response, true);
    
    if ($telegram_response && $telegram_response['ok']) {
        // Success - message sent to Telegram
        sendSuccessResponse('Thank you! Your message has been sent successfully. We\'ll get back to you soon.');
    } else {
        // Telegram API error
        logError("Telegram API Error", ['response' => $response]);
        sendErrorResponse('Message received but notification failed. We\'ll still get back to you.', 500);
    }
} else {
    // HTTP error or curl error
    logError("Telegram API HTTP Error", ['http_code' => $http_code, 'response' => $response]);
    sendErrorResponse('Message received but notification failed. We\'ll still get back to you.', 500);
}

// Log the contact form submission (optional)
$log_entry = date('Y-m-d H:i:s') . " | {$firstName} {$lastName} | {$email} | {$subject}\n";
file_put_contents('contact_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
?>

