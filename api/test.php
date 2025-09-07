<?php
/**
 * Test endpoint for Solite Candles API
 * 
 * This endpoint can be used to test the API functionality
 * and verify that the server is working correctly.
 */

require_once 'config.php';

// Set CORS headers
setCorsHeaders();
handlePreflight();

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle different test scenarios
switch ($method) {
    case 'GET':
        // Basic API test
        $test_data = [
            'api_name' => API_NAME,
            'api_version' => API_VERSION,
            'timestamp' => date('Y-m-d H:i:s'),
            'server_time' => time(),
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'request_method' => $method,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ];
        
        sendSuccessResponse('API is working correctly', $test_data);
        break;
        
    case 'POST':
        // Test POST functionality
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            sendErrorResponse('Invalid JSON data');
        }
        
        $test_data = [
            'received_data' => $input,
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => 'POST',
            'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'Unknown'
        ];
        
        sendSuccessResponse('POST test successful', $test_data);
        break;
        
    case 'OPTIONS':
        // Handle preflight requests
        http_response_code(200);
        exit;
        
    default:
        sendErrorResponse('Method not allowed', 405);
}

// Test Telegram connection (optional)
if (isset($_GET['test_telegram']) && $_GET['test_telegram'] === 'true') {
    $telegram_config = getTelegramConfig();
    
    if ($telegram_config['bot_token'] === 'YOUR_BOT_TOKEN_HERE' || 
        $telegram_config['chat_id'] === 'YOUR_CHAT_ID_HERE') {
        sendErrorResponse('Telegram not configured. Please set TELEGRAM_BOT_TOKEN and TELEGRAM_CHAT_ID environment variables.');
    }
    
    // Test Telegram API
    $telegram_url = "https://api.telegram.org/bot{$telegram_config['bot_token']}/getMe";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $telegram_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200 && $response) {
        $telegram_data = json_decode($response, true);
        if ($telegram_data && $telegram_data['ok']) {
            sendSuccessResponse('Telegram connection successful', [
                'bot_info' => $telegram_data['result'],
                'chat_id' => $telegram_config['chat_id']
            ]);
        } else {
            sendErrorResponse('Telegram API error: ' . $response);
        }
    } else {
        sendErrorResponse('Telegram connection failed: HTTP ' . $http_code);
    }
}
?>
