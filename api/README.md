# Solite Candles API

This directory contains the PHP API endpoints for the Solite Candles website.

## Files

- **`contact.php`** - Main contact form endpoint that sends messages to Telegram
- **`config.php`** - Shared configuration and utility functions
- **`webhook.php`** - Webhook endpoint for external integrations
- **`test.php`** - Test endpoint to verify API functionality

## Setup

### 1. Environment Variables

Set these environment variables on your hosting server:

```bash
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
```

### 2. Telegram Bot Setup

1. Create a bot with [@BotFather](https://t.me/botfather)
2. Get your bot token
3. Get your chat ID by messaging your bot and visiting:
   `https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getUpdates`

### 3. Testing

Test the API endpoints:

- **Basic test**: `GET /api/test.php`
- **Telegram test**: `GET /api/test.php?test_telegram=true`
- **Contact form**: `POST /api/contact.php`

## API Endpoints

### POST /api/contact.php

Handles contact form submissions.

**Request Body:**
```json
{
  "firstName": "John",
  "lastName": "Doe", 
  "email": "john@example.com",
  "subject": "Inquiry",
  "message": "Hello, I'm interested in your candles."
}
```

**Response:**
```json
{
  "success": true,
  "message": "Thank you! Your message has been sent successfully."
}
```

### GET /api/test.php

Tests API functionality.

**Response:**
```json
{
  "success": true,
  "message": "API is working correctly",
  "data": {
    "api_name": "Solite Candles API",
    "api_version": "1.0.0",
    "timestamp": "2024-01-01 12:00:00"
  }
}
```

## Features

- ✅ **CORS Support** - Cross-origin requests allowed
- ✅ **Rate Limiting** - Prevents spam (5 requests per hour)
- ✅ **Input Validation** - Sanitizes and validates all inputs
- ✅ **Error Logging** - Logs errors for debugging
- ✅ **Telegram Integration** - Sends notifications to Telegram
- ✅ **Security** - Prevents common attacks

## Logs

The API creates these log files:
- `contact_log.txt` - Contact form submissions
- `api_errors.log` - API errors
- `webhook_log.txt` - Webhook requests
- `rate_limit.json` - Rate limiting data

## Troubleshooting

1. **Check environment variables** are set correctly
2. **Test Telegram connection** with `/api/test.php?test_telegram=true`
3. **Check error logs** for detailed error messages
4. **Verify file permissions** for log files
