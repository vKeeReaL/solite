<?php
/**
 * Configuration file for Solite Candles API
 * 
 * This file contains configuration settings for the API endpoints
 * and should be included in other PHP files that need these settings.
 */

// API Configuration
define('API_VERSION', '1.0.0');
define('API_NAME', 'Solite Candles API');

// CORS Headers
function setCorsHeaders() {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 86400'); // 24 hours
}

// Handle preflight OPTIONS requests
function handlePreflight() {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

// Error logging function
function logError($message, $context = []) {
    $log_entry = date('Y-m-d H:i:s') . " | ERROR | " . $message;
    if (!empty($context)) {
        $log_entry .= " | Context: " . json_encode($context);
    }
    $log_entry .= "\n";
    file_put_contents('api_errors.log', $log_entry, FILE_APPEND | LOCK_EX);
}

// Success response function
function sendSuccessResponse($message, $data = []) {
    $response = ['success' => true, 'message' => $message];
    if (!empty($data)) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

// Error response function
function sendErrorResponse($message, $code = 400, $data = []) {
    http_response_code($code);
    $response = ['success' => false, 'message' => $message];
    if (!empty($data)) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

// Validate email function
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Sanitize input function
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Get environment variable with fallback
function getEnvVar($key, $default = null) {
    $value = getenv($key);
    return $value !== false ? $value : $default;
}

// Telegram Bot Configuration
function getTelegramConfig() {
    return [
        'bot_token' => getEnvVar('TELEGRAM_BOT_TOKEN', '8350406103:AAEC3FdHwh-Dd7FPFWDMvceBjF3hLK13GNU'),
        'chat_id' => getEnvVar('TELEGRAM_CHAT_ID', '343776702')
    ];
}

// Rate limiting (simple implementation)
function checkRateLimit($ip, $max_requests = 10, $time_window = 3600) {
    $rate_limit_file = 'rate_limit.json';
    $current_time = time();
    
    if (file_exists($rate_limit_file)) {
        $rate_data = json_decode(file_get_contents($rate_limit_file), true);
    } else {
        $rate_data = [];
    }
    
    // Clean old entries
    foreach ($rate_data as $stored_ip => $requests) {
        $rate_data[$stored_ip] = array_filter($requests, function($timestamp) use ($current_time, $time_window) {
            return ($current_time - $timestamp) < $time_window;
        });
    }
    
    // Check current IP
    if (!isset($rate_data[$ip])) {
        $rate_data[$ip] = [];
    }
    
    if (count($rate_data[$ip]) >= $max_requests) {
        return false; // Rate limit exceeded
    }
    
    // Add current request
    $rate_data[$ip][] = $current_time;
    file_put_contents($rate_limit_file, json_encode($rate_data), LOCK_EX);
    
    return true; // Within rate limit
}
?>
