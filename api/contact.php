<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['firstName', 'lastName', 'email', 'subject', 'message'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Missing required fields: ' . implode(', ', $missing_fields)
    ]);
    exit;
}

// Validate email
if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Sanitize input
$firstName = htmlspecialchars(trim($input['firstName']), ENT_QUOTES, 'UTF-8');
$lastName = htmlspecialchars(trim($input['lastName']), ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars(trim($input['email']), ENT_QUOTES, 'UTF-8');
$subject = htmlspecialchars(trim($input['subject']), ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars(trim($input['message']), ENT_QUOTES, 'UTF-8');

// Telegram Bot Configuration
// You need to set these values in your hosting environment
$bot_token = getenv('TELEGRAM_BOT_TOKEN') ?: 'YOUR_BOT_TOKEN_HERE';
$chat_id = getenv('TELEGRAM_CHAT_ID') ?: 'YOUR_CHAT_ID_HERE';

// If using environment variables, uncomment these lines:
// $bot_token = $_ENV['TELEGRAM_BOT_TOKEN'] ?? 'YOUR_BOT_TOKEN_HERE';
// $chat_id = $_ENV['TELEGRAM_CHAT_ID'] ?? 'YOUR_CHAT_ID_HERE';

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
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.'
        ]);
    } else {
        // Telegram API error
        error_log("Telegram API Error: " . $response);
        echo json_encode([
            'success' => false, 
            'message' => 'Message received but notification failed. We\'ll still get back to you.'
        ]);
    }
} else {
    // HTTP error or curl error
    error_log("Telegram API HTTP Error: " . $http_code . " - " . $response);
    echo json_encode([
        'success' => false, 
        'message' => 'Message received but notification failed. We\'ll still get back to you.'
    ]);
}

// Log the contact form submission (optional)
$log_entry = date('Y-m-d H:i:s') . " | {$firstName} {$lastName} | {$email} | {$subject}\n";
file_put_contents('contact_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
?>

