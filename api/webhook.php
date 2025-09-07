<?php
/**
 * Webhook endpoint for Solite Candles
 * 
 * This endpoint can be used for various webhook integrations
 * such as payment confirmations, order updates, etc.
 */

require_once 'config.php';

// Set CORS headers
setCorsHeaders();
handlePreflight();

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendErrorResponse('Method not allowed', 405);
}

// Get the raw POST data
$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

// Log the webhook request
$log_entry = date('Y-m-d H:i:s') . " | WEBHOOK | " . $raw_data . "\n";
file_put_contents('webhook_log.txt', $log_entry, FILE_APPEND | LOCK_EX);

// Basic webhook validation
if (!$data) {
    sendErrorResponse('Invalid JSON data');
}

// Check for required fields based on webhook type
$webhook_type = $data['type'] ?? 'unknown';

switch ($webhook_type) {
    case 'contact_form':
        // Handle contact form webhook
        if (empty($data['firstName']) || empty($data['email']) || empty($data['message'])) {
            sendErrorResponse('Missing required fields for contact form');
        }
        
        // Process contact form data
        $firstName = sanitizeInput($data['firstName']);
        $lastName = sanitizeInput($data['lastName'] ?? '');
        $email = sanitizeInput($data['email']);
        $subject = sanitizeInput($data['subject'] ?? 'Webhook Contact');
        $message = sanitizeInput($data['message']);
        
        // Validate email
        if (!validateEmail($email)) {
            sendErrorResponse('Invalid email format');
        }
        
        // Send to Telegram
        $telegram_config = getTelegramConfig();
        $telegram_message = "🕯️ *Webhook Contact Form*\n\n";
        $telegram_message .= "👤 *Name:* {$firstName} {$lastName}\n";
        $telegram_message .= "📧 *Email:* {$email}\n";
        $telegram_message .= "📝 *Subject:* {$subject}\n";
        $telegram_message .= "💬 *Message:*\n{$message}\n\n";
        $telegram_message .= "⏰ *Time:* " . date('Y-m-d H:i:s') . "\n";
        $telegram_message .= "🌐 *Source:* Webhook";
        
        $telegram_url = "https://api.telegram.org/bot{$telegram_config['bot_token']}/sendMessage";
        $telegram_data = [
            'chat_id' => $telegram_config['chat_id'],
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
        
        if ($http_code === 200 && $response) {
            sendSuccessResponse('Webhook processed successfully');
        } else {
            logError('Webhook Telegram error', ['http_code' => $http_code, 'response' => $response]);
            sendErrorResponse('Webhook processed but notification failed', 500);
        }
        break;
        
    case 'test':
        // Test webhook endpoint
        sendSuccessResponse('Webhook test successful', ['timestamp' => date('Y-m-d H:i:s')]);
        break;
        
    default:
        sendErrorResponse('Unknown webhook type: ' . $webhook_type);
}
?>
